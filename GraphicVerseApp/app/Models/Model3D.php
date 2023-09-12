<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model3D extends Model // Update the class name here
{
    use HasFactory;

    protected $fillable = ['threeD_name', 'description', 'cat_name', 'creator_name', 'filename', 'file_type', 'creator_username'];

    public function categories3D() // Update the function name here
    {
        return $this->belongsToMany(Categories::class, 'categories3_d_s', 'threeD_id', 'cat_id');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user3_d_s', 'threeD_id', 'user_id');
    }

    public function user3d() // Update the function name here
    {
        return $this->hasOne(User3D::class, 'threeD_id', 'id');
    }
}
