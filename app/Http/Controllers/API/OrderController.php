<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.product', 'items.vendor'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json($orders);
    }

    public function show(Request $request, int $id)
    {
        $order = Order::with(['items.product', 'items.vendor'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($order);
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'shipping_address'           => 'required|array',
            'shipping_address.name'      => 'required|string',
            'shipping_address.address'   => 'required|string',
            'shipping_address.city'      => 'required|string',
            'shipping_address.state'     => 'required|string',
            'shipping_address.zip'       => 'required|string',
            'shipping_address.country'   => 'required|string',
            'payment_method'             => 'required|string',
            'notes'                      => 'nullable|string',
            'items'                      => 'required|array|min:1',
            'items.*.product_id'         => 'required|integer|exists:products,id',
            'items.*.quantity'           => 'required|integer|min:1',
        ]);

        $cart = $data['items'];

        return DB::transaction(function () use ($cart, $data, $request) {
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}.");
                }

                $vendor = Vendor::findOrFail($product->vendor_id);
                $itemTotal = $product->price * $item['quantity'];
                $commissionRate = $vendor->commission_rate;
                $commissionAmount = ($itemTotal * $commissionRate) / 100;
                $vendorEarnings = $itemTotal - $commissionAmount;

                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product'          => $product,
                    'vendor'           => $vendor,
                    'quantity'         => $item['quantity'],
                    'price'            => $product->price,
                    'total'            => $itemTotal,
                    'commission_rate'  => $commissionRate,
                    'commission_amount'=> $commissionAmount,
                    'vendor_earnings'  => $vendorEarnings,
                ];

                $product->decrement('stock_quantity', $item['quantity']);
            }

            $tax = round($subtotal * 0.1, 2);
            $shipping = $subtotal > 100 ? 0 : 10;
            $totalAmount = $subtotal + $tax + $shipping;

            $order = Order::create([
                'user_id'          => $request->user()->id,
                'subtotal'         => $subtotal,
                'tax'              => $tax,
                'shipping'         => $shipping,
                'total_amount'     => $totalAmount,
                'payment_method'   => $data['payment_method'],
                'payment_status'   => 'pending',
                'order_status'     => 'pending',
                'shipping_address' => $data['shipping_address'],
                'notes'            => $data['notes'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create([
                    'product_id'       => $item['product']->id,
                    'vendor_id'        => $item['vendor']->id,
                    'product_name'     => $item['product']->name,
                    'quantity'         => $item['quantity'],
                    'price'            => $item['price'],
                    'total'            => $item['total'],
                    'commission_rate'  => $item['commission_rate'],
                    'commission_amount'=> $item['commission_amount'],
                    'vendor_earnings'  => $item['vendor_earnings'],
                ]);

                $item['vendor']->increment('total_earnings', $item['vendor_earnings']);
                $item['vendor']->increment('pending_payout', $item['vendor_earnings']);
            }

            return response()->json($order->load('items.product'), 201);
        });
    }
}
