<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'sku' => 'CA001',
                'name' => 'Cá hồi',
                'category_id' => 1,
                'storage_type' => 'live',
                'base_unit' => 'kg',
                'base_price' => 250000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'sku' => 'TOM001',
                'name' => 'Tôm sú',
                'category_id' => 2,
                'storage_type' => 'live',
                'base_unit' => 'kg',
                'base_price' => 300000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
