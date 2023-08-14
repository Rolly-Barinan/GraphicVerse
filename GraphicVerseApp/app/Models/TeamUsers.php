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
        'role',
    ];

    // Add a function to retrieve the role of a user in a specific team
    public function roleInTeam($teamId, $userId)
    {
        return $this->where('team_id', $teamId)->where('user_id', $userId)->value('role');
    }
}
