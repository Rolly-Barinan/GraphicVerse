<?php

namespace App\Http\Controllers;

use App\Models\ThreeD;
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


        return view('three-dim.create');
    }

    public function store(Request $request)
    {



        $data = request()->validate([
            'asset_name' => 'required',
            'asset' => 'required|file|mimes:bin,fbx',
            'category' => 'required',
            'description' => 'required',
        ]);

        $file = $data['asset'];
        $extension = $file->getClientOriginalExtension();
        $name = Str::random(40) . '.' . $extension;


        $assetPath = (request('asset')->store('3D', 'public'));

        auth()->user()->threeDs()->create([
            'asset_name' => $data['asset_name'],
            'category' => $data['category'],
            'description' => $data['description'],

            'asset' => $assetPath,
        ]);

        return redirect('/profile/' . auth()->user()->id);
    }
    public function show($id)

    {
        $user = auth()->user();
     

        $userThreeD = $user->threeDs()->findOrFail($id);
  

        return view('three-dim.show', compact('userThreeD',));
    }
}
