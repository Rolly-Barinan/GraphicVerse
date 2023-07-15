<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        return view('packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            $package = Package::create([
                'name' => $request->input('name'),
                'category' => $request->input('category'),
                'sub_category' => $request->input('sub_category'),
                'user_id' => $user->id, 
            ]);

            if ($request->hasFile('images')) {
                $files = $request->file('images');

                foreach ($files as $file) {
                    $filename = $file->store('images', 'public');

                        Image::create([
                        'filename' => $filename,
                        'package_id' => $package->id,
                    ]);
                }
            }

            return redirect()->route('profiles.profile')->with('success', 'Package created successfully.');
        }

        // Handle the case when the user is not authenticated
        return redirect()->route('login')->with('error', 'Please log in to create a package.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $packages = $user->packages;
          
    
            return view('packages.show', compact('packages', 'user'));
        }
    
        // Handle the case when the user is not authenticated
        return redirect()->route('login')->with('error', 'Please log in to view packages.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
}
