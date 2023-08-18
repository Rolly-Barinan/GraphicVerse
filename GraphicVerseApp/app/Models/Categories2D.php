<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories2D extends Model
{
    use HasFactory;
    public $table = 'categories2_d_s';
    public $timestamps = false;
    
    protected $fillable = [
        'cat_id',
        'twoD_id',
    ];
}
