<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamUsers extends Model
{
    use HasFactory;
    public $table = 'team_users';
    public $timestamps = false;
    
    protected $fillable = [
        'team_id',
        'user_id',
    ];
}
