<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Categories;
use App\Models\ImageAsset;
use App\Models\ImageCategory;
use App\Models\User;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageAssetController extends Controller
{

    public function index()
    {
        $images = ImageAsset::all();
        return view('image.index', compact('images'));
    }

    public function create()
    {
        $categories = Categories::all();
        $assetTypes = AssetType::all();
        return view('image.create', compact('assetTypes', 'categories'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'ImageName' => 'required',
            'Price' => 'required',
            'imageFile' => 'required|image|mimes:jpeg,png,jpg,gif',
            'watermarkFile' => 'image|mimes:jpeg,png,jpg,gif',
            'category_ids' => 'array',
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Store the original image file
        $imageFile = $request->file('imageFile');

        // Get the original file name
        $originalFileName = $imageFile->getClientOriginalName();

        // Create an instance of Intervention Image
        $image = Image::make($imageFile);
        $origImage = Image::make($imageFile);
        // Check if a watermark file is provided
        if ($request->hasFile('watermarkFile')) {
            $watermarkFile = $request->file('watermarkFile');
            $watermark = Image::make($watermarkFile);

            // Fit the watermark to the dimensions of the original image
            $watermark->fit($image->width(), $image->height());

            // Insert the watermark onto the original image
            $image->insert($watermark, 'center');
        }

        // Define directory for storing images
        $directory = 'public/images/';
        $watermarkDir = 'public/watermark/';

        // Ensure directories exist, create them if not
        File::ensureDirectoryExists(storage_path('app/public/' . $directory));
        File::ensureDirectoryExists(storage_path('app/public/' . $watermarkDir));

        // Generate a unique filename for the image
        $filename = $imageFile->hashName();

        // Save the image to the desired location
        $image->save(storage_path('app/' . $watermarkDir . $filename));
        $origImage->save(storage_path('app/' . $directory . $filename));

        // Create a new ImageAsset instance with the provided data
        $imageAsset = new ImageAsset([
            'userID' => $user->id,
            'assetTypeID' => 1,
            'ImageName' => $request->input('ImageName'),
            'ImageDescription' => $request->input('ImageDescription'),
            'Location' =>  $directory . $filename,
            'Price' => $request->input('Price'),
            'ImageSize' => $imageFile->getSize(),
            'watermarkedImage' =>   $watermarkDir . $filename,
        ]);

        // Save the ImageAsset instance to the database
        $imageAsset->save();

        // Attach categories to the ImageAsset
        $imageAsset->categories()->attach($request->input('category_ids', []));

        // Redirect to the image create page with a success message
        return redirect()->route('image.create')->with('success', 'ImageAsset created successfully!');
    }


    public function show($id)
    {
        $image = ImageAsset::findOrFail($id);
        $userId = $image->userID;
        $user = User::find($userId);
        $imageSize = $image->ImageSize / 1024;;
        return view('image.show', compact('image', 'user', 'imageSize'));
    }

    public function download($id)
    {
        $image = ImageAsset::findOrFail($id);
        $filePath = storage_path('app/public/' . $image->Location);

        if (!file_exists($filePath)) {
            abort(404);
        }
        $fileName = basename($image->Location);
        return response()->download($filePath, $fileName);
    }

    public function destroy(ImageAsset $image)
    {
 
        $origImage = $image->Location;
        $watermark = $image->watermarkedImage;
        if (Storage::exists($origImage)) {
            Storage::delete($origImage);
        }   
        if (Storage::exists($watermark)) {
            Storage::delete($watermark);
        }
        $image->delete();
        return redirect('/image')->with('success', 'Image asset deleted successfully.');
    }
    
    public function filterImage(Request $request)
    {
        $categoryIds = $request->input('categories');

        if (!is_array($categoryIds) || empty($categoryIds)) {
            $images = ImageAsset::all();
        } else {
            $images = ImageAsset::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })->get();
        }
        $categories = Categories::all();
        return view('image.index', compact('images', 'categories'));
    }
}
