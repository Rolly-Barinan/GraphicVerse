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
        'likes',
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
    public function purchases()
    {
       return $this->hasMany(Purchase::class); 
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'packagelikes', 'package_id', 'user_id');
    }
}
