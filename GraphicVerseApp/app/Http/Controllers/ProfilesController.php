<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Intevention\Image\Facades\Image;

class ProfilesController extends Controller
{
        public function index(User $user)
        {

                return view('profiles.profile', compact('user'));
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

                auth()->user()->profile->update($data);

                if( request('image')){
                        
                }

                return redirect("/profile/{$user->id}");
        }
}
