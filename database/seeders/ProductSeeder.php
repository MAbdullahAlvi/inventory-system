<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'sku' => 'SKU-1001',
                'name' => 'Wireless Mouse',
                'description' => 'High-quality wireless mouse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'SKU-1002',
                'name' => 'Mechanical Keyboard',
                'description' => 'Backlit mechanical keyboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

