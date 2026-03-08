<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('user')
            ->where('status', 'approved')
            ->withCount('products')
            ->paginate(20);

        return response()->json($vendors);
    }

    public function show(string $slug)
    {
        $vendor = Vendor::with(['user', 'products' => fn($q) => $q->active()])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        return response()->json($vendor);
    }
}
