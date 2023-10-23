<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = ['cat_name'];

    public function model2D()
    {
        return $this->belongsToMany(model2D::class, 'categories2_d_s', 'cat_id', 'twoD_id');
    }

    public function model3D()
    {
        return $this->belongsToMany(model3D::class, 'categories3_d_s', 'cat_id', 'threeD_id');
    }
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_category', 'category_id', 'package_id');
    }
   


}
