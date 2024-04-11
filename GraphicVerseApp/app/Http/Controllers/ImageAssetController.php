<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Categories;
use App\Models\ImageAsset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Faker\Provider\Image;



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
        // Validate the incoming request data
        $request->validate([
            'ImageName' => 'required|string|max:255',
            'ImageDescription' => 'nullable|string',
            'Price' => 'nullable|numeric|min:0',
            'imageFile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max size as needed
            'watermarkFile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max size as needed
            'category_ids' => 'nullable|array', // Ensure it's an array
            'category_ids.*' => 'exists:categories,id', // Ensure each category id exists in the database
        ]);

        // Retrieve the authenticated user
        $userID = Auth::id();

        // Upload the image file to the public/images directory
        $imagePath = $request->file('imageFile')->store('public/images');

        // Get the relative path
        $imagePath = str_replace('public/', '', $imagePath);

        // Apply watermark if provided
        if ($request->hasFile('watermarkFile')) {
            // Create an instance of Intervention Image for the original image
            $imageFile = $request->file('imageFile');
            $image = Image::make($imageFile);

            // Create an instance of Intervention Image for the watermark
            $watermarkFile = $request->file('watermarkFile');
            $watermark = Image::make($watermarkFile);

            // Fit the watermark to the dimensions of the original image
            $watermark->fit($image->width(), $image->height());

            // Insert the watermark onto the original image
            $image->insert($watermark, 'center');
            dd($image);
            // Save the watermarked image to the public/watermarks directory
            $watermarkedImagePath = $request->file('imageFile')->store('public/watermarks');

            // Get the relative path
            $watermarkedImagePath = str_replace('public/', '', $watermarkedImagePath);
        } else {
            // If no watermark provided, use the original image path
            $watermarkedImagePath = $imagePath;
        }

        // Get image size
        $imageSize = $request->file('imageFile')->getSize();

        // Create the image asset
        $imageAsset = ImageAsset::create([
            'userID' => $userID, // Assign the authenticated user's ID
            'assetTypeID' => $request->input('asset_type_id'),
            'ImageName' => $request->input('ImageName'),
            'ImageDescription' => $request->input('ImageDescription'),
            'Price' => $request->input('Price'),
            'Location' => $imagePath,
            'watermarkedImage' => $watermarkedImagePath, // Save the watermark path to the database
            'ImageSize' => $imageSize,
        ]);

        // Attach categories to the image asset
        if ($request->has('category_ids')) {
            $imageAsset->categories()->attach($request->input('category_ids'));
        }

        // Redirect to a success page or return a response as needed
        return redirect()->route('image.create')->with('success', 'ImageAsset created successfully.');
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
