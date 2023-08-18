<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User2D extends Model
{
    use HasFactory;

    public $table = 'user2_d_s';
    public $timestamps = false;
    
    protected $fillable = [
        'twoD_id',
        'user_id',
    ];
}
