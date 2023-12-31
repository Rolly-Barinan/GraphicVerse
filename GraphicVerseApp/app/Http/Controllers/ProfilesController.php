<?php

namespace App\Http\Controllers;

use App\Models\ThreeD;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
        public function index(User $user)
        {
                // Fetch user's 2D uploads
                $userUploads = $user->model2D()->get();
                $userUploads3D = $user->model3D()->get();
                $userTeams = $user->teams; // Fetch user's teams
                
                // $userThreeDs = $user->threeDs()->get();

                // $fbxFiles = auth()->user()->threeDs->pluck('asset')->toArray();
                

                return view('profiles.profile', compact('user', 'userUploads3D', 'userTeams', 'userUploads'));
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

                ]);
       
                
                if (request('image')) {
                        $imagePath = (request('image')->store('profile', 'public'));

                        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
                        $image->save();
                        $imageArray = ['image' => $imagePath];
                }

                auth()->user()->profile->update(array_merge(
                        $data,
                        $imageArray ?? []
                ));

                return redirect("/profile/{$user->id}");
        }
}
