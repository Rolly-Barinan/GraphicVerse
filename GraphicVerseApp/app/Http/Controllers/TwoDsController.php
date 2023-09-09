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
    public function index(Request $request) {
        $categories = Categories::all();
        $selectedCategories = $request->input('categories', []);
        $models2DQuery = Model2D::query();
    
        if (!empty($selectedCategories)) {
            $models2DQuery->whereHas('categories2D', function ($query) use ($selectedCategories) {
                $query->whereIn('cat_id', $selectedCategories);
            });
        }

    
        // Sort models based on the selected option
        $sort = $request->input('sort', 'default'); // 'default' is your default sorting method
    
        switch ($sort) {
            case 'name_asc':
                $models2DQuery->orderBy('twoD_name', 'asc');
                break;
            case 'name_desc':
                $models2DQuery->orderBy('twoD_name', 'desc');
                break;
            case 'date_asc':
                $models2DQuery->orderBy('created_at', 'asc'); // Assuming 'created_at' is the published date column
                break;
            case 'date_desc':
                $models2DQuery->orderBy('created_at', 'desc'); // Assuming 'created_at' is the published date column
                break;
            default:
                // Handle the default sorting case here
                break;
        }
        // Add more sorting options and their corresponding orderBy clauses as needed
        


        $models2D = $models2DQuery->paginate(12); // Paginate with 12 records per page
    
        return view('two-dim.index', compact('models2D', 'categories', 'selectedCategories', 'dateFilter'));
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
            'categories' => 'required|array',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Save the uploaded image to the '2D' folder
        $imagePath = $request->file('image')->store('2D', 'public');

        // Get selected category names as a comma-separated string
        $selectedCategoryNames = Categories::whereIn('id', $request->input('categories'))->pluck('cat_name')->join(', ');

        // Create a new Model2D entry
        $model2D = Model2D::create([
            'twoD_name' => $request->input('package_name'),
            'description' => $request->input('description'),
            'creator_name' => Auth::user()->name,
            'cat_name' => $selectedCategoryNames, // Store selected category names
            'filename' => $imagePath,
        ]);

        // Attach the selected categories to the model2D
        $model2D->categories2D()->attach($request->input('categories'));

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
    public function edit($id, Request $request)
    {
        // Retrieve the specific Model2D instance
        $model2D = Model2D::findOrFail($id);

        // Check if the currently authenticated user is the creator/owner of the image
        if (Auth::user()->id !== $model2D->user2d->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to edit this image.');
        }

        $categories = Categories::all();

        // Get the selected categories for the current model
        $selectedCategories = $model2D->categories2D->pluck('id')->toArray();

        return view('two-dim.edit', compact('model2D', 'categories', 'selectedCategories'));
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
        $model2D = Model2D::findOrFail($id);

        // Check if the currently authenticated user is the creator/owner of the image
        if (Auth::user()->id !== $model2D->user2d->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to edit this image.');
        }

        // Get selected category names as a comma-separated string
        $selectedCategoryNames = Categories::whereIn('id', $request->input('categories'))->pluck('cat_name')->join(', ');

        // Update the Model2D entry
        $model2D->update([
            'twoD_name' => $request->input('package_name'),
            'description' => $request->input('description'),
            'cat_name' => $selectedCategoryNames, // Store selected category names
        ]);

        // Sync the selected categories to the model2D
        $model2D->categories2D()->sync($request->input('categories'));

        return redirect()->route('twoD.show', $model2D->id)->with('success', '2D asset updated successfully.');
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
        $model2D = Model2D::findOrFail($id);

        // Check if the currently authenticated user is the creator/owner of the image
        if (Auth::user()->id !== $model2D->user2d->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to delete this image.');
        }

        // Delete the associated image file from storage
        Storage::disk('public')->delete($model2D->filename);

        // Detach the associated categories
        $model2D->categories2D()->detach();

        // Delete the User2D entry
        $model2D->user2d->delete();

        // Delete the Model2D entry
        $model2D->delete();

        return redirect()->route('profile.show', ['user' => Auth::user()])->with('success', '2D asset deleted successfully.');
    }
}
