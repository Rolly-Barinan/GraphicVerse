<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $fillable = [
        'AssetName', // Add 'AssetName' to the $fillable array
        'FileType',
        'FileSize',
        'Location',
        'UserID',
        'PackageID'
    ];
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
