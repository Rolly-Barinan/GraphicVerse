<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ImageType;

class ImageTypesSeeder extends Seeder
{
    public function run()
    {
        $imageTypes = [
            'jpeg',
            'png',
            'jpg',
            'gif',
            // Add more image types as needed
        ];

        foreach ($imageTypes as $type) {
            ImageType::create(['name' => $type]);
        }
    }
}

