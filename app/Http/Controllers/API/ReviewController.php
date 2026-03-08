<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, int $productId)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating'   => 'required|integer|min:1|max:5',
            'title'    => 'nullable|string|max:255',
            'comment'  => 'nullable|string|max:2000',
        ]);

        $product = Product::findOrFail($productId);

        $hasPurchased = $request->user()
            ->orders()
            ->where('id', $data['order_id'])
            ->whereHas('items', fn($q) => $q->where('product_id', $productId))
            ->where('order_status', 'delivered')
            ->exists();

        if (!$hasPurchased) {
            return response()->json(['message' => 'You can only review products you have purchased.'], 403);
        }

        $review = Review::create([
            'product_id'  => $productId,
            'user_id'     => $request->user()->id,
            'order_id'    => $data['order_id'],
            'rating'      => $data['rating'],
            'title'       => $data['title'] ?? null,
            'comment'     => $data['comment'] ?? null,
            'is_verified' => true,
        ]);

        $avgRating = Review::where('product_id', $productId)->where('is_approved', true)->avg('rating');
        $count = Review::where('product_id', $productId)->where('is_approved', true)->count();
        $product->update(['average_rating' => round($avgRating, 2), 'reviews_count' => $count]);

        return response()->json($review->load('user'), 201);
    }
}
