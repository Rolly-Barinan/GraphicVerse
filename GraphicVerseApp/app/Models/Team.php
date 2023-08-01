<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'color'];
    
    public function members()
    {
        return $this->hasMany(User::class);
    }
}
