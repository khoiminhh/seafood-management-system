<?php

namespace App\Services;

use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public static function consumeInventory($productId, $quantity)
    {
        // FIFO consumption
        $batches = Inventory::where('product_id', $productId)->where('quantity_current','>',0)->orderBy('date_received')->get();
        $remaining = $quantity;

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;
            $take = min($batch->quantity_current, $remaining);
            $batch->quantity_current -= $take;
            if ($batch->quantity_current <= 0) $batch->status = 'sold_out';
            $batch->save();
            $remaining -= $take;
        }

        return $remaining == 0;
    }
}
