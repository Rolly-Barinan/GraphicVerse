<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Model2D;
use App\Models\Categories;
use App\Models\Categories2D;
use App\Models\User2D;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TwoDsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Categories::all();
        $selectedCategories = $request->input('categories', []);
        
        $models2DQuery = Model2D::query();

        if (!empty($selectedCategories)) {
            $models2DQuery->whereHas('categories2D', function ($query) use ($selectedCategories) {
                $query->whereIn('cat_id', $selectedCategories);
            });
        }

        $models2D = $models2DQuery->get();

        return view('two-dim.index', compact('models2D', 'categories', 'selectedCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $categories = Categories::all();
        return view('two-dim.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Save the uploaded image to the '2D' folder
        $imagePath = $request->file('image')->store('2D', 'public');

        // Create a new Model2D entry
        $model2D = Model2D::create([
            'twoD_name' => $request->input('package_name'),
            'description' => $request->input('description'),
            'cat_name' => $request->input('category'),
            'creator_name' => Auth::user()->name, // Assuming the user is authenticated
            'filename' => $imagePath,
        ]);

        // Attach the category to the model2D
        $model2D->categories2D()->attach($request->input('category'));
        
        // Create a User2D entry to associate the authenticated user with the uploaded model
        User2D::create([
            'twoD_id' => $model2D->id,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('profile.show', ['user' => Auth::user()])->with('success', '2D asset uploaded successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Retrieve the specific Model2D instance
        $model2D = Model2D::findOrFail($id);

        return view('two-dim.show', compact('model2D'));
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
