<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID', 'assetTypeID', 'ImageName', 'ImageDescription', 'Location', 'Price', 'ImageSize', 'watermarkedImage', 'likes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class, 'assetTypeID');
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'image_category', 'imageAsset_id', 'category_id');
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
        return $this->belongsToMany(User::class, 'likes', 'image_asset_id', 'user_id');
    }
}
