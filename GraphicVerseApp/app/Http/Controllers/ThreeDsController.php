<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class ThreeDsController extends Controller
{
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
        // dd(request()->all());

        auth()->user()->threeDs()->create($data);
  
    }
}
