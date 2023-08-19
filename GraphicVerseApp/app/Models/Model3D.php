<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model3D extends Model
{
    use HasFactory;
    protected $fillable = ['threeD_name', 'description', 'cat_name', 'creator_name', 'filename'];

    public function categories3D()
    {
        return $this->belongsToMany(Categories::class, 'categories3_d_s', 'threeD_id', 'cat_id');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user3_d_s', 'threeD_id', 'user_id');
    }
}
