<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'color', 'code'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_users', 'team_id', 'user_id')->withPivot('role');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

}
