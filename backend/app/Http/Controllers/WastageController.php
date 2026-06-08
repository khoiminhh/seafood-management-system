<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WastageController extends Controller
{
    public function record(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|numeric',
            'reason' => 'required'
        ]);

        $id = DB::table('wastage')->insertGetId([
            'inventory_id' => $request->get('inventory_id'),
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'reason' => $data['reason'],
            'description' => $request->get('description'),
            'recorded_by' => $request->user()->id ?? null,
            'recorded_at' => now(),
            'created_at' => now()
        ]);

        return response()->json(['success' => true, 'wastage_id' => $id], 201);
    }
}
