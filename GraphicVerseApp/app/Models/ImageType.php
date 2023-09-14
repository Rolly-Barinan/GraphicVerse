<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function model2D()
    {
        return $this->hasMany(Model2D::class, 'image_type', 'name');
    }
}
