<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Cá', 'slug' => 'ca'],
            ['name' => 'Tôm', 'slug' => 'tom'],
            ['name' => 'Cua', 'slug' => 'cua'],
            ['name' => 'Ngao Sò', 'slug' => 'ngao-so'],
            ['name' => 'Đồ khô', 'slug' => 'do-kho'],
            ['name' => 'Đồ đông lạnh', 'slug' => 'dong-lanh'],
            ['name' => 'Hải sản chế biến', 'slug' => 'che-bien']
        ];

        foreach ($categories as $c) {
            DB::table('categories')->insert(array_merge($c, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
