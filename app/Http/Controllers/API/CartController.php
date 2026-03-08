<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        return response()->json($this->formatCart($cart));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::active()->findOrFail($data['product_id']);

        if ($product->stock_quantity < $data['quantity']) {
            return response()->json(['message' => 'Insufficient stock.'], 422);
        }

        $cart = $request->session()->get('cart', []);
        $key = (string) $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $data['quantity'];
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'quantity'   => $data['quantity'],
                'image'      => $product->images[0] ?? null,
                'slug'       => $product->slug,
                'vendor_id'  => $product->vendor_id,
            ];
        }

        $request->session()->put('cart', $cart);

        return response()->json($this->formatCart($cart));
    }

    public function update(Request $request, int $productId)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $request->session()->get('cart', []);
        $key = (string) $productId;

        if (!isset($cart[$key])) {
            return response()->json(['message' => 'Item not in cart.'], 404);
        }

        $cart[$key]['quantity'] = $data['quantity'];
        $request->session()->put('cart', $cart);

        return response()->json($this->formatCart($cart));
    }

    public function remove(Request $request, int $productId)
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[(string) $productId]);
        $request->session()->put('cart', $cart);

        return response()->json($this->formatCart($cart));
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart');
        return response()->json(['items' => [], 'total' => 0, 'count' => 0]);
    }

    private function formatCart(array $cart): array
    {
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return [
            'items' => array_values($cart),
            'total' => round($total, 2),
            'count' => array_sum(array_column($cart, 'quantity')),
        ];
    }
}
