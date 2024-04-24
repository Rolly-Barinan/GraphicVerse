<?php

namespace App\Http\Controllers;

use App\Models\ImageAsset;
use App\Models\Package;
use App\Models\ThreeD;
use App\Models\User;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
        public function index(User $user)
        {
                // Fetch user's 2D uploads                         
                $userTeams = $user->teams; // Fetch user's teams               
                // $userThreeDs = $user->threeDs()->get();
                // $fbxFiles = auth()->user()->threeDs->pluck('asset')->toArray();
                return view('profiles.profile', compact('user',  'userTeams'));
        }

        public function edit(User $user)
        {
                $this->authorize('update', $user->profile);

                return view('profiles.edit', compact('user'));
        }

        public function update(User $user)
        {
        $this->authorize('update', $user->profile);
        $data = request()->validate([
                'title' => 'required',
                'description' => 'required',
                'url' => 'url',
                'image' => '',
                'cover_image' => '', // Add this line
        ]);

        if (request('image')) {
                $imagePath = (request('image')->store('profile', 'public'));
                $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
                $image->save();
                $imageArray = ['image' => $imagePath];
        }

        if (request('cover_image')) { // Add this block
                $coverImagePath = (request('cover_image')->store('cover', 'public'));
                $coverImage = Image::make(public_path("storage/{$coverImagePath}"))->fit(1200, 400);
                $coverImage->save();
                $coverImageArray = ['cover_image' => $coverImagePath];
        }

        auth()->user()->profile->update(array_merge(
                $data,
                $imageArray ?? [],
                $coverImageArray ?? [] // Add this line
        ));

        return redirect("/profile/{$user->id}");
        }

        public function twoDimDisplay()
        {
                $user = auth()->user();
                $packages = Package::where('UserID', $user->id)->get();

                return view('profiles.twoDimDisplay', compact('packages'));
        }

        public function threeDimDisplay()
        {
                $user = auth()->user();
                $packages = Package::where('UserID', $user->id)->get();

                return view('profiles.threeDimDisplay', compact('packages'));
        }

        public function audioDisplay()
        {
                $user = auth()->user();
                $packages = Package::where('UserID', $user->id)->get();
                return view('profiles.audioDisplay', compact('packages'));
        }

        public function imageDisplay()
        {
                $user = auth()->user();
                $images = ImageAsset::where('UserID', $user->id)->get();
                
                return view('profiles.imageDisplay', compact('images'));
        }
        
}
