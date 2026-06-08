<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $list = Supplier::paginate(20);
        return response()->json(['success' => true, 'data' => $list]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);

        $supplier = Supplier::create($data);
        return response()->json(['success' => true, 'data' => $supplier], 201);
    }
}
