<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'store_name', 'slug', 'description', 'logo', 'banner',
        'phone', 'address', 'commission_rate', 'status', 'total_earnings', 'pending_payout',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($vendor) {
            if (empty($vendor->slug)) {
                $vendor->slug = Str::slug($vendor->store_name);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }
}
