<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model2D extends Model
{
    use HasFactory;

    protected $fillable = ['twoD_name', 'description', 'cat_name', 'creator_name', 'filename'];

    public function categories2D()
    {
        return $this->belongsToMany(Categories::class, 'categories2_d_s', 'twoD_id', 'cat_id');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user2_d_s', 'twoD_id', 'user_id');
    }
}
