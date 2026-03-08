<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function vendor(Request $request)
    {
        return $request->user()->vendor;
    }

    public function index(Request $request)
    {
        $products = Product::with('category')
            ->where('vendor_id', $this->vendor($request)->id)
            ->latest()
            ->paginate(20);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'full_description' => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'compare_price'    => 'nullable|numeric|min:0',
            'category_id'      => 'required|exists:categories,id',
            'stock_quantity'   => 'required|integer|min:0',
            'sku'              => 'nullable|string|unique:products,sku',
            'images'           => 'nullable|array',
            'status'           => 'in:active,inactive,draft',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data['vendor_id'] = $this->vendor($request)->id;
        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();

        $product = Product::create($data);

        return response()->json($product->load('category'), 201);
    }

    public function show(Request $request, int $id)
    {
        $product = Product::where('vendor_id', $this->vendor($request)->id)->findOrFail($id);
        return response()->json($product->load('category'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::where('vendor_id', $this->vendor($request)->id)->findOrFail($id);

        $data = $request->validate([
            'name'             => 'sometimes|string|max:255',
            'description'      => 'nullable|string',
            'full_description' => 'nullable|string',
            'price'            => 'sometimes|numeric|min:0',
            'compare_price'    => 'nullable|numeric|min:0',
            'category_id'      => 'sometimes|exists:categories,id',
            'stock_quantity'   => 'sometimes|integer|min:0',
            'sku'              => 'nullable|string|unique:products,sku,' . $product->id,
            'images'           => 'nullable|array',
            'status'           => 'in:active,inactive,draft',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $product->update($data);

        return response()->json($product->load('category'));
    }

    public function destroy(Request $request, int $id)
    {
        $product = Product::where('vendor_id', $this->vendor($request)->id)->findOrFail($id);
        $product->update(['status' => 'inactive']);

        return response()->json(['message' => 'Product deactivated.']);
    }
}
