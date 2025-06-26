<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        Inventory::insert([
            [
                'product_id' => 1,
                'quantity' => 100,
                'location' => 'Warehouse A',
                'cost' => 10.50,
                'lot_number' => 'LOT-A1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'quantity' => 50,
                'location' => 'Warehouse B',
                'cost' => 25.00,
                'lot_number' => 'LOT-B2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

