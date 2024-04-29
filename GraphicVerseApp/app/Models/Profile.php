<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function profileImage()
    {
        $imagePath = ($this->image) ? $this->image : 'profile/profileImage.png';
        return '/storage/' . $imagePath;
    }
    public function coverImage()
    {
        $coverImagePath = ($this->cover_image) ? $this->cover_image : 'profile/coverPhoto.png';
        return '/storage/' . $coverImagePath;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
