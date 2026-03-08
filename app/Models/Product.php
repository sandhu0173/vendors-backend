<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'category_id', 'name', 'slug', 'description', 'full_description',
        'price', 'compare_price', 'stock_quantity', 'sku', 'images', 'status',
        'average_rating', 'reviews_count', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'average_rating' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
