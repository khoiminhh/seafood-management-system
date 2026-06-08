<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public static function createOrder(array $payload)
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => 'ORD'.time(),
                'customer_id' => $payload['customer_id'],
                'order_type' => $payload['order_type'] ?? 'online',
                'status' => 'pending',
                'subtotal' => 0,
                'total_amount' => 0
            ]);

            $subtotal = 0;
            foreach ($payload['items'] as $it) {
                $unit_price = $it['unit_price'] ?? 0;
                $line = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $it['product_id'],
                    'variant_id' => $it['variant_id'] ?? null,
                    'quantity' => $it['quantity'],
                    'unit_price' => $unit_price,
                    'processing_services' => $it['processing_services'] ?? [],
                    'original_total' => $unit_price * $it['quantity']
                ]);
                $subtotal += $line->original_total;
            }

            $order->update(['subtotal' => $subtotal, 'total_amount' => $subtotal + ($payload['shipping_cost'] ?? 0)]);
            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
