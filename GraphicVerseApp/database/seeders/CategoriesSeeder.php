<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['category' => 'vehicle'],
            ['category' => 'environment'],
            ['category' => 'wallpaper'],
            // Add more categories as needed
        ];

        DB::table('categories')->insert($categories);
    }
}
