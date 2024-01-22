<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;



    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_category', 'category_id', 'package_id');
    }

    public function imageAssets()
    {
        return $this->belongsToMany(ImageAsset::class, 'image_category', 'category_id', 'imageAsset_id');
    }

}
