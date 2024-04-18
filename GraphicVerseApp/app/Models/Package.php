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
        'Price',
        'UserID',
        'asset_type_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'PackageID', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'package_category', 'package_id', 'category_id');
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
