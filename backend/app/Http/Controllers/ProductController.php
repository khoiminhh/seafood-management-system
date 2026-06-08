<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        if ($request->has('storage_type')) {
            $query->where('storage_type', $request->get('storage_type'));
        }

        $products = $query->paginate($request->get('per_page', 20));

        return response()->json(['success' => true, 'data' => $products]);
    }

    public function show($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $product]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'required|unique:products,sku',
            'name' => 'required',
            'category_id' => 'required|integer',
            'base_price' => 'required|numeric'
        ]);

        $product = Product::create([
            'sku' => $data['sku'],
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'base_price' => $data['base_price'],
            'storage_type' => $request->get('storage_type', 'live'),
            'base_unit' => $request->get('base_unit', 'kg')
        ]);

        return response()->json(['success' => true, 'data' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json(['success' => true, 'data' => $product]);
    }
}
