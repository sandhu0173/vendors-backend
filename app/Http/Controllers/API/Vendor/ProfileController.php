<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user()->vendor->load('user'));
    }

    public function update(Request $request)
    {
        $vendor = $request->user()->vendor;

        $data = $request->validate([
            'store_name'  => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|string',
            'banner'      => 'nullable|string',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
        ]);

        $vendor->update($data);

        return response()->json($vendor->fresh()->load('user'));
    }
}
