<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetTypeSeeder extends Seeder
{
    
    public function run()
    {
        $assetTypes = [
            ['asset_type' => '2D'],
            ['asset_type' => '3D'],
            ['asset_type' => 'Animations'],
          
        ];
        DB::table('asset_types')->insert($assetTypes);
    }
}
