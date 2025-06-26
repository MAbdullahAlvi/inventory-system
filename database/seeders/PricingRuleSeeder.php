<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingRule;

class PricingRuleSeeder extends Seeder
{
    public function run(): void
    {
        PricingRule::insert([

            [
                'product_id' => 1,
                'type' => 'time',
                'discount_percentage' => 5.00,
                'min_quantity' => null,
                'start_time' => '00:00:00',
                'end_time' => '23:59:59',
                'days' => 'Saturday,Sunday',
                'precedence' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],


            [
                'product_id' => 1,
                'type' => 'quantity',
                'discount_percentage' => 10.00,
                'min_quantity' => 10,
                'start_time' => null,
                'end_time' => null,
                'days' => null,
                'precedence' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
