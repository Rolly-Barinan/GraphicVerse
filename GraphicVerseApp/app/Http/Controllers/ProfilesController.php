<?php

namespace App\Http\Controllers;

use App\Models\ThreeD;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfilesController extends Controller
{
        public function index(User $user)
        {
                // Fetch user's 2D uploads
                $userUploads = $user->model2D()->get();
                $userTeams = $user->teams; // Fetch user's teams

                $perPage = 8; // Number of items per page for 2D uploads
                $currentPage = request()->get('page', 1); // Use the default 'page' query parameter

                // Create a LengthAwarePaginator instance for 2D uploads
                $userUploadsPaginated = new LengthAwarePaginator(
                        $userUploads->slice(($currentPage - 1) * $perPage, $perPage),
                        $userUploads->count(),
                        $perPage,
                        $currentPage,
                        ['path' => request()->url(), 'query' => request()->query()]
                );

                $userUploads3d = $user->model3D()->get();
                $perPage3D = 6; // Number of items per page for 3D uploads
                $currentPage3D = request()->get('page3d', 1); // Use a custom 'page3d' query parameter for 3D uploads

                // Create a LengthAwarePaginator instance for 3D uploads
                $userUploads3DPaginated = new LengthAwarePaginator(
                        $userUploads3d->slice(($currentPage3D - 1) * $perPage3D, $perPage3D),
                        $userUploads3d->count(),
                        $perPage3D,
                        $currentPage3D,
                        ['path' => request()->url(), 'query' => request()->query()]
                );



                return view('profiles.profile', compact('user', 'userUploadsPaginated', 'userTeams', 'userUploads', 'userUploads3DPaginated','userUploads3d' ));
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
