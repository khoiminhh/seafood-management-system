<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|integer',
            'items' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => 'ORD'.time(),
                'customer_id' => $data['customer_id'],
                'order_type' => $request->get('order_type','online'),
                'status' => 'pending',
                'subtotal' => 0,
                'total_amount' => 0
            ]);

            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $unit_price = $item['unit_price'] ?? 0;
                $line = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unit_price,
                    'processing_services' => json_encode($item['processing_services'] ?? []),
                    'original_total' => $unit_price * $item['quantity']
                ]);
                $subtotal += $line->original_total;
            }

            $order->update(['subtotal' => $subtotal, 'total_amount' => $subtotal + ($request->get('shipping_cost',0))]);
            DB::commit();
            return response()->json(['success' => true, 'data' => $order], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateWeight(Request $request, $id)
    {
        $data = $request->validate([
            'actual_weight' => 'required|numeric',
            'items' => 'required|array'
        ]);

        $order = Order::findOrFail($id);
        foreach ($data['items'] as $it) {
            $item = OrderItem::findOrFail($it['item_id']);
            $item->actual_quantity = $it['actual_quantity'];
            $item->actual_total = $item->unit_price * $it['actual_quantity'];
            $item->save();
        }

        $order->actual_weight = $data['actual_weight'];
        $order->actual_total = OrderItem::where('order_id',$order->id)->sum('actual_total');
        $order->status = 'confirmed';
        $order->save();

        // TODO: Trigger notification to customer (SMS/Zalo)

        return response()->json(['success' => true, 'data' => $order]);
    }

    public function show($id)
    {
        $order = Order::with('items.product','items.variant')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $order]);
    }
}
