<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User3D extends Model
{
    use HasFactory;

    public $table = 'user3_d_s';
    public $timestamps = false;
    
    protected $fillable = [
        'threeD_id',
        'user_id',
    ];
}
