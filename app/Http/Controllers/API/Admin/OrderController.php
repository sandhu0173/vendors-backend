<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items.vendor'])
            ->when($request->status, fn($q, $s) => $q->where('order_status', $s))
            ->latest()
            ->paginate(20);

        return response()->json($orders);
    }

    public function show(int $id)
    {
        $order = Order::with(['user', 'items.product', 'items.vendor'])->findOrFail($id);
        return response()->json($order);
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        $order = Order::findOrFail($id);
        $order->update($data);

        return response()->json(['message' => 'Order status updated.', 'order' => $order]);
    }

    public function stats()
    {
        return response()->json([
            'total_orders'    => Order::count(),
            'total_revenue'   => Order::where('payment_status', 'paid')->sum('total_amount'),
            'pending_orders'  => Order::where('order_status', 'pending')->count(),
            'total_vendors'   => \App\Models\Vendor::where('status', 'approved')->count(),
            'total_products'  => \App\Models\Product::where('status', 'active')->count(),
            'total_customers' => \App\Models\User::where('role', 'customer')->count(),
        ]);
    }
}
