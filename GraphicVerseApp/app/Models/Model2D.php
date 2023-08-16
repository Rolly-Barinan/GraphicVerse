<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model2D extends Model
{
    use HasFactory;

    protected $fillable = ['twoD_name', 'description', 'category_name', 'creator_name'];

    public function categories2D()
    {
        return $this->belongsToMany(Categories::class, 'categories2_d_s', 'twoD_id', 'cat_id');
    }
    
    public function user2D()
    {
        return $this->belongsToMany(User::class, 'user2_d_s', 'twoD_id', 'user_id');
    }
}
