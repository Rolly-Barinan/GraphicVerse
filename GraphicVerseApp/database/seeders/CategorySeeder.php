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
            ['cat_name'=> 'Characters'],
            ['cat_name' => 'Vehicles'],
            ['cat_name' => 'Props'],
            ['cat_name' => 'Vegetation'],
            ['cat_name' => 'Environment'],
        ];

        DB::table('categories')->insert($category);
    }
}
