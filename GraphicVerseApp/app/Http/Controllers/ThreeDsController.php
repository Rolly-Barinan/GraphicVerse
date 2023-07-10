<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThreeDsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('threeDs/create');
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'asset' => 'required|file|mimes:bin,fbx',
        ]);

        $file = $data['asset'];
        $extension = $file->getClientOriginalExtension();
        $name = Str::random(40).'.'.$extension;

        $assetPath = $file->storeAs('public/3D', $name);
        
        auth()->user()->threeDs()->create([
            'name' => $data['name'],
            'asset' => $assetPath,
        ]);
        
        return redirect('/profile/'. auth()->user()->id);
    }
}
