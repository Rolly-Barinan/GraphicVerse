<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create([
                'title' => $user->username,

            ]);
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function threeDs()

    {
        return $this->hasMany(ThreeD::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class)->orderBy('created_at', 'DESC');
    }
    public function audios()
    {
        return $this->hasMany(Audio::class);
    }

    //////////////////////////////////////////
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users', 'user_id', 'team_id')->withPivot('role');;
    }

    public function model2D()
    {
        return $this->belongsToMany(Model2D::class, 'user2_d_s', 'user_id', 'twoD_id');
    }

    public function model3D()
    {
        return $this->belongsToMany(Model3D::class, 'user3_d_s', 'user_id', 'threeD_id');
    }
}
