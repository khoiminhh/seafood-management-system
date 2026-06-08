<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::query();
        if ($request->has('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }
        $data = $query->paginate($request->get('per_page', 20));
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'batch_number' => 'required',
            'quantity_in' => 'required|numeric',
            'date_received' => 'required|date'
        ]);

        $inventory = Inventory::create(array_merge($data, ['quantity_current' => $data['quantity_in']]));
        return response()->json(['success' => true, 'data' => $inventory], 201);
    }

    public function wastage(Request $request)
    {
        $data = $request->validate([
            'inventory_id' => 'nullable|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|numeric',
            'reason' => 'required'
        ]);

        // Simple record: insert into wastage table
        $id = DB::table('wastage')->insertGetId([
            'inventory_id' => $data['inventory_id'] ?? null,
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
