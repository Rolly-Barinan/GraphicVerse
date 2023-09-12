<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Model3D;
use App\Models\Categories;
use App\Models\Categories3D;
use App\Models\User3D;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ThreeDsController2 extends Controller
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
        $models3DQuery = Model3D::query();
    
        if (!empty($selectedCategories)) {
            $models3DQuery->whereHas('categories3D', function ($query) use ($selectedCategories) {
                $query->whereIn('cat_id', $selectedCategories);
            });
        }
    
        // Sort models based on the selected option
        $sort = $request->input('sort', 'default'); // 'default' is your default sorting method
    
        switch ($sort) {
            case 'name_asc':
                $models3DQuery->orderBy('threeD_name', 'asc'); // Update this to threeD_name
                break;
            case 'name_desc':
                $models3DQuery->orderBy('threeD_name', 'desc'); // Update this to threeD_name
                break;
            case 'date_asc':
                $models3DQuery->orderBy('created_at', 'asc'); // Assuming 'created_at' is the published date column
                break;
            case 'date_desc':
                $models3DQuery->orderBy('created_at', 'desc'); // Assuming 'created_at' is the published date column
                break;
            default:
                // Handle the default sorting case here
                break;
        }
        // Add more sorting options and their corresponding orderBy clauses as needed
    
        $models3D = $models3DQuery->paginate(12); // Define $models3D here
    
        return view('three-dim.index', compact('models3D', 'categories', 'selectedCategories'));

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $categories = Categories::all();
        return view('three-dim.create', compact('categories'));
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
            'categories' => 'required|array',
            'threeD_asset' => 'required|file|mimes:bin,fbx',
        ]);

        // Save the uploaded file to the '3D' folder
        $assetPath = $request->file('threeD_asset')->store('3D', 'public');

        // Get selected category names as a comma-separated string
        $selectedCategoryNames = Categories::whereIn('id', $request->input('categories'))->pluck('cat_name')->join(', ');

        // Create a new Model3D entry
        $model3D = Model3D::create([
            'threeD_name' => $request->input('package_name'),
            'description' => $request->input('description'),
            'cat_name' => $selectedCategoryNames, // Store selected category names
            'creator_name' => Auth::user()->name, // Assuming the user is authenticated
            'filename' => $assetPath,
        ]);

         // Attach the selected categories to the model3D
         $model3D->categories3D()->attach($request->input('categories'));
        
        // Create a User3D entry to associate the authenticated user with the uploaded model
        User3D::create([
            'threeD_id' => $model3D->id,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('profile.show', ['user' => Auth::user()])->with('success', '3D asset uploaded successfully.');
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
        $model3D = Model3D::findOrFail($id);

        return view('three-dim.show', compact('model3D'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        // Retrieve the specific Model2D instance
        $model3D = Model3D::findOrFail($id);

        // Check if the currently authenticated user is the creator/owner of the image
        if (Auth::user()->id !== $model3D->user3d->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to edit this image.');
        }

        $categories = Categories::all();

        // Get the selected categories for the current model
        $selectedCategories = $model3D->categories3D->pluck('id')->toArray();

        return view('three-dim.edit', compact('model3D', 'categories', 'selectedCategories'));
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
        // Validate the input
        $request->validate([
            'package_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'categories' => 'required|array',
        ]);

        // Retrieve the specific Model2D instance
        $model3D = Model3D::findOrFail($id);

        // Check if the currently authenticated user is the creator/owner of the image
        if (Auth::user()->id !== $model3D->user3d->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to edit this image.');
        }

        // Get selected category names as a comma-separated string
        $selectedCategoryNames = Categories::whereIn('id', $request->input('categories'))->pluck('cat_name')->join(', ');

        // Update the Model2D entry
        $model3D->update([
            'threeD_name' => $request->input('package_name'),
            'description' => $request->input('description'),
            'cat_name' => $selectedCategoryNames, // Store selected category names
        ]);

        // Sync the selected categories to the model2D
        $model3D->categories3D()->sync($request->input('categories'));

        return redirect()->route('threeD.show', $model3D->id)->with('success', '3D asset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Retrieve the specific Model2D instance
        $model3D = Model3D::findOrFail($id);

        // Check if the currently authenticated user is the creator/owner of the image
        if (Auth::user()->id !== $model3D->user3d->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to delete this image.');
        }

        // Delete the associated image file from storage
        Storage::disk('public')->delete($model3D->filename);

        // Detach the associated categories
        $model3D->categories3D()->detach();

        // Delete the User3D entry
        $model3D->user3d->delete();

        // Delete the Model3D entry
        $model3D->delete();

        return redirect()->route('profile.show', ['user' => Auth::user()])->with('success', '3D asset deleted successfully.');
    }
}
