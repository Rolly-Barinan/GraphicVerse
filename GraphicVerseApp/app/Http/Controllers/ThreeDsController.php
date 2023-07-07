<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



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
            // 'asset' => ['required', 'file', 'mimes:fbx'],
            'asset' => 'required',
        ]);
 
        $assetPath = request('asset')->store('3D', 'public'); //3D is directory under storage/public
        auth()->user()->threeDs()->create([
            'name' => $data['name'],
            'asset' => $assetPath,
        ]);
        return redirect('/profile/'. auth()->user()->id);

    }
}
