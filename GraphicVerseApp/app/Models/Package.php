<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'sub_category',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function thumbnail()
    {
        return $this->hasOne(Image::class, 'package_id')->orderBy('created_at', 'desc');
    }
}
