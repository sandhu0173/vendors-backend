<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('user')->withCount('products')->paginate(20);
        return response()->json($vendors);
    }

    public function show(int $id)
    {
        $vendor = Vendor::with(['user', 'products'])->findOrFail($id);
        return response()->json($vendor);
    }

    public function approve(int $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['status' => 'approved']);
        $vendor->user->update(['role' => 'vendor']);

        return response()->json(['message' => 'Vendor approved.', 'vendor' => $vendor]);
    }

    public function suspend(int $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['status' => 'suspended']);

        return response()->json(['message' => 'Vendor suspended.', 'vendor' => $vendor]);
    }

    public function updateCommission(Request $request, int $id)
    {
        $data = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($data);

        return response()->json(['message' => 'Commission updated.', 'vendor' => $vendor]);
    }
}
