<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@marketplace.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Categories
        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'is_active' => true]);
        $clothing    = Category::create(['name' => 'Clothing', 'slug' => 'clothing', 'is_active' => true]);
        $books        = Category::create(['name' => 'Books', 'slug' => 'books', 'is_active' => true]);
        $home         = Category::create(['name' => 'Home & Garden', 'slug' => 'home-garden', 'is_active' => true]);

        // Sub-categories
        Category::create(['name' => 'Smartphones', 'slug' => 'smartphones', 'parent_id' => $electronics->id, 'is_active' => true]);
        Category::create(['name' => 'Laptops', 'slug' => 'laptops', 'parent_id' => $electronics->id, 'is_active' => true]);
        Category::create(['name' => 'Men\'s Clothing', 'slug' => 'mens-clothing', 'parent_id' => $clothing->id, 'is_active' => true]);
        Category::create(['name' => 'Women\'s Clothing', 'slug' => 'womens-clothing', 'parent_id' => $clothing->id, 'is_active' => true]);

        // Vendor 1
        $vendor1User = User::create([
            'name'     => 'Tech Store',
            'email'    => 'vendor1@marketplace.com',
            'password' => Hash::make('password'),
            'role'     => 'vendor',
        ]);

        $vendor1 = Vendor::create([
            'user_id'         => $vendor1User->id,
            'store_name'      => 'Tech World',
            'slug'            => 'tech-world',
            'description'     => 'Your one-stop shop for electronics and gadgets.',
            'commission_rate' => 10.00,
            'status'          => 'approved',
        ]);

        // Vendor 2
        $vendor2User = User::create([
            'name'     => 'Fashion Store',
            'email'    => 'vendor2@marketplace.com',
            'password' => Hash::make('password'),
            'role'     => 'vendor',
        ]);

        $vendor2 = Vendor::create([
            'user_id'         => $vendor2User->id,
            'store_name'      => 'Style Hub',
            'slug'            => 'style-hub',
            'description'     => 'Trendy fashion for everyone.',
            'commission_rate' => 12.00,
            'status'          => 'approved',
        ]);

        // Customer
        User::create([
            'name'     => 'John Customer',
            'email'    => 'customer@marketplace.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);

        // Products for Vendor 1
        $products = [
            ['name' => 'Wireless Headphones Pro', 'price' => 79.99, 'stock' => 50, 'category' => $electronics->id],
            ['name' => 'USB-C Hub 7-in-1', 'price' => 39.99, 'stock' => 100, 'category' => $electronics->id],
            ['name' => 'Mechanical Keyboard RGB', 'price' => 129.99, 'stock' => 30, 'category' => $electronics->id],
            ['name' => 'Portable SSD 1TB', 'price' => 89.99, 'stock' => 45, 'category' => $electronics->id],
        ];

        foreach ($products as $p) {
            Product::create([
                'vendor_id'      => $vendor1->id,
                'category_id'    => $p['category'],
                'name'           => $p['name'],
                'slug'           => Str::slug($p['name']),
                'description'    => "High quality {$p['name']} for everyday use.",
                'price'          => $p['price'],
                'stock_quantity' => $p['stock'],
                'status'         => 'active',
                'meta_title'     => $p['name'] . ' | Tech World',
            ]);
        }

        // Products for Vendor 2
        $fashionProducts = [
            ['name' => 'Classic White T-Shirt', 'price' => 24.99, 'stock' => 200],
            ['name' => 'Slim Fit Jeans', 'price' => 59.99, 'stock' => 80],
            ['name' => 'Summer Floral Dress', 'price' => 49.99, 'stock' => 60],
            ['name' => 'Leather Jacket', 'price' => 149.99, 'stock' => 25],
        ];

        foreach ($fashionProducts as $p) {
            Product::create([
                'vendor_id'      => $vendor2->id,
                'category_id'    => $clothing->id,
                'name'           => $p['name'],
                'slug'           => Str::slug($p['name']),
                'description'    => "Stylish and comfortable {$p['name']}.",
                'price'          => $p['price'],
                'stock_quantity' => $p['stock'],
                'status'         => 'active',
                'meta_title'     => $p['name'] . ' | Style Hub',
            ]);
        }
    }
}
