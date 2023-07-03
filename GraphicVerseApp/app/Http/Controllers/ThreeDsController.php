<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThreeDsController extends Controller
{
    public function create()
    {
        return view('threeDs/create');
    }
}
