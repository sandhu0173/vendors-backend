<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private function vendor(Request $request)
    {
        return $request->user()->vendor;
    }

    public function index(Request $request)
    {
        $items = OrderItem::with(['order.user', 'product'])
            ->where('vendor_id', $this->vendor($request)->id)
            ->latest()
            ->paginate(20);

        return response()->json($items);
    }

    public function stats(Request $request)
    {
        $vendorId = $this->vendor($request)->id;

        $totalOrders = OrderItem::where('vendor_id', $vendorId)->distinct('order_id')->count('order_id');
        $totalRevenue = OrderItem::where('vendor_id', $vendorId)->sum('vendor_earnings');
        $totalProducts = \App\Models\Product::where('vendor_id', $vendorId)->count();
        $pendingOrders = OrderItem::where('vendor_id', $vendorId)
            ->whereHas('order', fn($q) => $q->where('order_status', 'pending'))
            ->distinct('order_id')->count('order_id');

        return response()->json([
            'total_orders'    => $totalOrders,
            'total_revenue'   => $totalRevenue,
            'total_products'  => $totalProducts,
            'pending_orders'  => $pendingOrders,
        ]);
    }
}
