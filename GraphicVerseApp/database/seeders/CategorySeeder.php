<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = [
            ['cat_name'=> 'Characters', 'created_at' => now()],
            ['cat_name' => 'Vehicles', 'created_at' => now()],
            ['cat_name' => 'Props', 'created_at' => now()],
            ['cat_name' => 'Vegetation', 'created_at' => now()],
            ['cat_name' => 'Environment', 'created_at' => now()],
        ];

        DB::table('categories')->insert($category);
    }
}
