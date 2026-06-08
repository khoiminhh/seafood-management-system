<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        DB::table('suppliers')->insert([
            ['name' => 'Vựa Hải Sản 1', 'phone' => '0901234001', 'city' => 'Nha Trang', 'supplier_type' => 'market', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tàu Cá 12', 'phone' => '0901234002', 'city' => 'Quảng Ninh', 'supplier_type' => 'boat', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
