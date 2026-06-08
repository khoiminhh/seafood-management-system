<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));

        $total_revenue = DB::table('orders')->whereDate('created_at', $date)->sum('total_amount');
        $total_orders = DB::table('orders')->whereDate('created_at', $date)->count();

        // for demo: COGS approx base_price * qty from order_items joined
        $bestsellers = DB::select("SELECT p.name as product, SUM(oi.quantity) as quantity, SUM(oi.original_total) as revenue FROM order_items oi JOIN products p ON p.id = oi.product_id JOIN orders o ON o.id = oi.order_id WHERE DATE(o.created_at) = ? GROUP BY oi.product_id ORDER BY quantity DESC LIMIT 10", [$date]);

        return response()->json([
            'success' => true,
            'total_revenue' => $total_revenue,
            'total_orders' => $total_orders,
            'bestsellers' => $bestsellers
        ]);
    }
}
