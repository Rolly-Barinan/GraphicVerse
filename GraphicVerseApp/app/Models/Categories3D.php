<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories3D extends Model
{
    use HasFactory;

    public $table = 'categories3_d_s';
    public $timestamps = false;
    
    protected $fillable = [
        'cat_id',
        'threeD_id',
    ];
}
