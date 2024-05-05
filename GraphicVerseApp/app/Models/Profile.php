<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $defaultProfileImage = 'https://www.creativefabrica.com/wp-content/uploads/2022/10/29/Astronaut-In-A-Space-Suit-With-Planets-In-The-Background-43811989-1.png';
    protected $defaultCoverImage = 'https://timelinecovers.pro/facebook-cover/download/space-and-universe-facebook-cover.jpg';

    public function profileImage()
    {
        // If user has uploaded an image, return its URL, otherwise return default profile image URL
        return $this->image ? '/storage/' . $this->image : $this->defaultProfileImage;
    }

    public function coverImage()
    {
        // If user has uploaded a cover image, return its URL, otherwise return default cover image URL
        return $this->cover_image ? '/storage/' . $this->cover_image : $this->defaultCoverImage;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
