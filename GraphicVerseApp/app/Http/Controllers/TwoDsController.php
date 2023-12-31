<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Model2D;
use App\Models\Categories;
use App\Models\ImageType;
use App\Models\Categories2D;
use App\Models\User2D;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
        // Get available image types from the database
        $imageTypes = ImageType::all();

        // Selected image types from the request
        $selectedImageTypes = $request->input('image_type', []);

        if (!empty($selectedImageTypes)) {
            $models2DQuery->whereIn('image_type', $selectedImageTypes);
        }


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



        $models2D = $models2DQuery->paginate(12);

        return view('two-dim.index', compact('models2D', 'categories', 'selectedCategories', 'imageTypes', 'selectedImageTypes'));
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

        // Determine file type based on MIME
        $uploadedFile = $request->file('image');
        $fileType = $uploadedFile->getMimeType();
        // Remove the "image/" prefix from the MIME type
        $fileType = str_replace('image/', '', $fileType);
        // Save the uploaded image to the '2D' folder
        $imagePath = $uploadedFile->store('2D', 'public');

        // Get selected category names as a comma-separated string
        $selectedCategoryNames = Categories::whereIn('id', $request->input('categories'))->pluck('cat_name')->join(', ');

        // Create a new Model2D entry with the file type
        $model2D = Model2D::create([
            'twoD_name' => $request->input('package_name'),
            'description' => $request->input('description'),
            'creator_username' => Auth::user()->username,
            'cat_name' => $selectedCategoryNames, // Store selected category names
            'filename' => $imagePath,
            'image_type' => $fileType, // Store the detected file type
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


    public function download($id)
    {
        // Retrieve the specific Model2D instance
        $model2D = Model2D::findOrFail($id);
    
        // Implement logic to check if the user is allowed to download the image
        // You can add your logic here, for example, checking if it's a free download or if the user has purchased it.
    
        // Assuming it's a free download, you can serve the image file with a watermark
        $path = storage_path('app/public/' . $model2D->filename);
    
        // Check if the file exists
        if (file_exists($path)) {
            // Load the original image
            $image = Image::make($path);
    
            // Load the watermark image and resize it to match the size of the original image
            $watermark = Image::make(public_path('svg/watermark.png'));
            $watermark->resize($image->width(), $image->height());
    
            // Overlay the watermark on the image without specifying an X and Y position
            $image->insert($watermark, 'center');
    
            // Define a directory for storing watermarked images
            $watermarkedDirectory = storage_path('app/public/watermarked/');
    
            // Ensure the directory exists, or create it if it doesn't
            if (!file_exists($watermarkedDirectory)) {
                mkdir($watermarkedDirectory, 0755, true);
            }
    
            // Define the path for the watermarked image
            $watermarkedImagePath = $watermarkedDirectory . $model2D->twoD_name . '-watermarked.png';
    
            // Save the watermarked image
            $image->save($watermarkedImagePath);
    
            // Serve the watermarked image for download
            return response()->download($watermarkedImagePath); // Adjust the filename as needed
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
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
