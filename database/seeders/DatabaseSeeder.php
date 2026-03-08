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

        // Products for Vendor 1 (Tech World)
        $techProducts = [
            [
                'name'             => 'Wireless Headphones Pro',
                'price'            => 79.99,
                'compare_price'    => 99.99,
                'stock'            => 50,
                'category'         => $electronics->id,
                'description'      => 'Premium wireless headphones with active noise cancellation, 30-hour battery life, and crystal-clear sound. Perfect for music lovers and professionals.',
                'full_description' => '<h3>Features</h3><ul><li>Active Noise Cancellation (ANC)</li><li>30-hour battery life with quick charge</li><li>Bluetooth 5.2 with multipoint connection</li><li>Foldable design with carrying case</li><li>Built-in microphone for calls</li></ul><h3>Specifications</h3><p>Driver: 40mm dynamic | Frequency: 20Hz–20kHz | Impedance: 32Ω | Weight: 250g</p>',
                'meta_description' => 'Buy Wireless Headphones Pro with ANC, 30hr battery, and Bluetooth 5.2. Best wireless headphones for music and calls at Tech World.',
                'images'           => [
                    'https://picsum.photos/seed/headphones-pro-1/600/600',
                    'https://picsum.photos/seed/headphones-pro-2/600/600',
                    'https://picsum.photos/seed/headphones-pro-3/600/600',
                ],
            ],
            [
                'name'             => 'USB-C Hub 7-in-1',
                'price'            => 39.99,
                'compare_price'    => 54.99,
                'stock'            => 100,
                'category'         => $electronics->id,
                'description'      => 'Expand your laptop connectivity with this compact 7-in-1 USB-C hub. Includes 4K HDMI, 3× USB-A 3.0, SD/microSD card readers, and 100W PD charging pass-through.',
                'full_description' => '<h3>Ports</h3><ul><li>1× HDMI 2.0 (4K@60Hz)</li><li>3× USB-A 3.0 (5Gbps)</li><li>1× SD card reader</li><li>1× microSD card reader</li><li>1× USB-C PD (100W pass-through)</li></ul><h3>Compatibility</h3><p>Works with MacBook, iPad Pro, Windows laptops, and any USB-C device.</p>',
                'meta_description' => 'Buy USB-C Hub 7-in-1 with 4K HDMI, USB-A 3.0, SD reader, and 100W PD charging. Compatible with MacBook and Windows laptops.',
                'images'           => [
                    'https://picsum.photos/seed/usb-hub-7in1-1/600/600',
                    'https://picsum.photos/seed/usb-hub-7in1-2/600/600',
                ],
            ],
            [
                'name'             => 'Mechanical Keyboard RGB',
                'price'            => 129.99,
                'compare_price'    => 159.99,
                'stock'            => 30,
                'category'         => $electronics->id,
                'description'      => 'Tactile mechanical keyboard with per-key RGB lighting, hot-swappable switches, and a durable aluminium frame. Built for gamers and productivity enthusiasts.',
                'full_description' => '<h3>Key Features</h3><ul><li>Hot-swappable mechanical switches (Red/Blue/Brown available)</li><li>Per-key RGB with 16M colour customisation</li><li>Aluminium top plate for premium feel</li><li>N-key rollover anti-ghosting</li><li>USB-C detachable cable</li></ul><h3>Layout</h3><p>TKL (Tenkeyless) layout | 87 keys | Double-shot PBT keycaps</p>',
                'meta_description' => 'Buy Mechanical Keyboard RGB with hot-swap switches, per-key RGB, and aluminium frame. Perfect for gaming and typing.',
                'images'           => [
                    'https://picsum.photos/seed/mech-keyboard-rgb-1/600/600',
                    'https://picsum.photos/seed/mech-keyboard-rgb-2/600/600',
                    'https://picsum.photos/seed/mech-keyboard-rgb-3/600/600',
                ],
            ],
            [
                'name'             => 'Portable SSD 1TB',
                'price'            => 89.99,
                'compare_price'    => 109.99,
                'stock'            => 45,
                'category'         => $electronics->id,
                'description'      => 'Ultra-fast portable SSD with 1TB capacity and read speeds up to 1,050 MB/s. Compact, shock-resistant, and compatible with PC, Mac, iPhone 15+, and Android.',
                'full_description' => '<h3>Performance</h3><ul><li>Read speed: up to 1,050 MB/s</li><li>Write speed: up to 1,000 MB/s</li><li>Interface: USB 3.2 Gen 2 (USB-C)</li></ul><h3>Durability</h3><ul><li>IP55 water and dust resistance</li><li>2m drop protection</li></ul><h3>Compatibility</h3><p>Windows, macOS, iPad Pro, iPhone 15, Android (OTG required).</p>',
                'meta_description' => 'Buy Portable SSD 1TB with 1050MB/s speed, USB-C, and IP55 resistance. Works with PC, Mac, iPhone 15, and Android.',
                'images'           => [
                    'https://picsum.photos/seed/portable-ssd-1tb-1/600/600',
                    'https://picsum.photos/seed/portable-ssd-1tb-2/600/600',
                ],
            ],
        ];

        foreach ($techProducts as $p) {
            Product::create([
                'vendor_id'        => $vendor1->id,
                'category_id'      => $p['category'],
                'name'             => $p['name'],
                'slug'             => Str::slug($p['name']),
                'description'      => $p['description'],
                'full_description' => $p['full_description'],
                'price'            => $p['price'],
                'compare_price'    => $p['compare_price'],
                'stock_quantity'   => $p['stock'],
                'status'           => 'active',
                'images'           => $p['images'],
                'meta_title'       => $p['name'] . ' | Tech World',
                'meta_description' => $p['meta_description'],
            ]);
        }

        // Products for Vendor 2 (Style Hub)
        $fashionProducts = [
            [
                'name'             => 'Classic White T-Shirt',
                'price'            => 24.99,
                'compare_price'    => 34.99,
                'stock'            => 200,
                'description'      => 'A timeless wardrobe essential. Crafted from 100% organic cotton for all-day comfort and a clean, minimalist look that pairs with anything.',
                'full_description' => '<h3>Details</h3><ul><li>100% organic cotton (180 GSM)</li><li>Crew neck with reinforced stitching</li><li>Preshrunk to minimise shrinkage</li><li>Available in sizes XS–3XL</li></ul><h3>Care</h3><p>Machine wash cold, tumble dry low. Do not bleach.</p>',
                'meta_description' => 'Buy Classic White T-Shirt made from 100% organic cotton. Timeless crew neck design available in XS–3XL. Shop Style Hub.',
                'images'           => [
                    'https://picsum.photos/seed/white-tshirt-classic-1/600/600',
                    'https://picsum.photos/seed/white-tshirt-classic-2/600/600',
                ],
            ],
            [
                'name'             => 'Slim Fit Jeans',
                'price'            => 59.99,
                'compare_price'    => 79.99,
                'stock'            => 80,
                'description'      => 'Modern slim-fit jeans with a touch of stretch for comfort. Designed with a classic 5-pocket style, perfect for casual outings and smart-casual occasions.',
                'full_description' => '<h3>Details</h3><ul><li>98% cotton, 2% elastane for stretch comfort</li><li>Slim fit through thigh and leg opening</li><li>Classic 5-pocket design</li><li>Mid-rise waist</li><li>Available in washes: Indigo, Light Blue, Black</li></ul><h3>Care</h3><p>Machine wash cold, hang dry for best results.</p>',
                'meta_description' => 'Buy Slim Fit Jeans with stretch comfort and classic 5-pocket style. Available in multiple washes. Shop Style Hub.',
                'images'           => [
                    'https://picsum.photos/seed/slim-fit-jeans-1/600/600',
                    'https://picsum.photos/seed/slim-fit-jeans-2/600/600',
                    'https://picsum.photos/seed/slim-fit-jeans-3/600/600',
                ],
            ],
            [
                'name'             => 'Summer Floral Dress',
                'price'            => 49.99,
                'compare_price'    => 69.99,
                'stock'            => 60,
                'description'      => 'Light, breezy, and beautifully printed — this summer floral dress is made from breathable chiffon with a flattering A-line silhouette. Ideal for beach days, brunch, or garden parties.',
                'full_description' => '<h3>Details</h3><ul><li>100% chiffon — lightweight and breathable</li><li>All-over floral print</li><li>A-line silhouette with smocked waist</li><li>V-neck with flutter sleeves</li><li>Available in sizes XS–2XL</li></ul><h3>Care</h3><p>Hand wash cold or dry clean. Do not wring.</p>',
                'meta_description' => 'Buy Summer Floral Dress in lightweight chiffon with A-line silhouette and smocked waist. Perfect for beach and brunch. Shop Style Hub.',
                'images'           => [
                    'https://picsum.photos/seed/summer-floral-dress-1/600/600',
                    'https://picsum.photos/seed/summer-floral-dress-2/600/600',
                ],
            ],
            [
                'name'             => 'Leather Jacket',
                'price'            => 149.99,
                'compare_price'    => 199.99,
                'stock'            => 25,
                'description'      => 'A genuine leather biker jacket combining edge and versatility. Features a zippered front, asymmetric hem, and quilted lining — built to last and gets better with age.',
                'full_description' => '<h3>Details</h3><ul><li>Genuine full-grain leather exterior</li><li>Quilted polyester lining for warmth</li><li>Asymmetric front zip with snap-button collar</li><li>4 exterior pockets + 2 interior pockets</li><li>Available in Black, Brown, Dark Red</li></ul><h3>Care</h3><p>Wipe clean with a damp cloth. Apply leather conditioner every 3–6 months.</p>',
                'meta_description' => 'Buy genuine leather biker jacket with quilted lining and 6 pockets. Available in black, brown, and dark red. Shop Style Hub.',
                'images'           => [
                    'https://picsum.photos/seed/leather-jacket-1/600/600',
                    'https://picsum.photos/seed/leather-jacket-2/600/600',
                    'https://picsum.photos/seed/leather-jacket-3/600/600',
                ],
            ],
        ];

        foreach ($fashionProducts as $p) {
            Product::create([
                'vendor_id'        => $vendor2->id,
                'category_id'      => $clothing->id,
                'name'             => $p['name'],
                'slug'             => Str::slug($p['name']),
                'description'      => $p['description'],
                'full_description' => $p['full_description'],
                'price'            => $p['price'],
                'compare_price'    => $p['compare_price'],
                'stock_quantity'   => $p['stock'],
                'status'           => 'active',
                'images'           => $p['images'],
                'meta_title'       => $p['name'] . ' | Style Hub',
                'meta_description' => $p['meta_description'],
            ]);
        }
    }
}
