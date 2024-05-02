<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'color', 'code', 'profile_picture','cover_picture'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_users', 'team_id', 'user_id')->withPivot('role');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}