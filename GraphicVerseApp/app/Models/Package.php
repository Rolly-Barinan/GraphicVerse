<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = [
        'PackageName', 
        'Description',
        'preview',
        'Location',
        'UserID',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assets()
{
    return $this->hasMany(Asset::class, 'PackageID', 'id');
}
}
