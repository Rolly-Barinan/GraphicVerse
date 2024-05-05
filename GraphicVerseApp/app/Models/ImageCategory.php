<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageCategory extends Model
{
    use HasFactory;
    protected $table = 'image_category';
    public function image()
    {
        return $this->belongsToMany(ImageAsset::class, 'id', 'imageAsset_id', 'category_id');
    }

}
