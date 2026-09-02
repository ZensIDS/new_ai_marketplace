<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Marketplace',
            'email' => 'admin@marketplace.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '628123456789',
        ]);

        // Customer contoh
        User::create([
            'name' => 'Budi Customer',
            'email' => 'customer@marketplace.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '628987654321',
        ]);

        // Kategori
        $categories = ['Elektronik', 'Fashion Pria', 'Fashion Wanita', 'Kesehatan & Kecantikan', 'Peralatan Rumah'];
        $categoryModels = collect($categories)->map(fn ($name) => Category::create([
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(4),
        ]));

        // Produk contoh
        $products = [
            ['Headphone Bluetooth X1', 250000, 'Elektronik'],
            ['Kaos Polos Premium', 85000, 'Fashion Pria'],
            ['Dress Casual Wanita', 175000, 'Fashion Wanita'],
            ['Serum Wajah Vitamin C', 95000, 'Kesehatan & Kecantikan'],
            ['Rice Cooker Mini 1L', 210000, 'Peralatan Rumah'],
            ['Power Bank 20000mAh', 275000, 'Elektronik'],
            ['Jaket Hoodie Unisex', 150000, 'Fashion Pria'],
            ['Tas Selempang Wanita', 130000, 'Fashion Wanita'],
        ];

        foreach ($products as [$name, $price, $catName]) {
            $category = $categoryModels->firstWhere('name', $catName);
            Product::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => Str::slug($name) . '-' . Str::random(5),
                'description' => 'Produk berkualitas ' . $name . '. Cocok untuk kebutuhan sehari-hari dengan harga terjangkau.',
                'price' => $price,
                'stock' => rand(5, 50),
                'is_active' => true,
            ]);
        }
    }
}
