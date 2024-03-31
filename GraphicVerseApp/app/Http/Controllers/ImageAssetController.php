<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Categories;
use App\Models\ImageAsset;
use App\Models\User;
use Illuminate\Http\Request;
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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to upload images.');
        }

        // Validate the form data
        $validatedData = $request->validate([
            'ImageName' => 'required',
            'Price' => 'required',
            'imageFile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'watermarkFile' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_ids' => 'array',
        ]);

        $user = auth()->user();

        $imagePath = $request->file('imageFile')->store('images', 'public');

        $watermarkPath = null;

        if ($request->hasFile('watermarkFile')) {
            $watermarkPath = $request->file('watermarkFile')->store('watermarks', 'public');
        }

        // Create a new ImageAsset instance
        $imageAsset = new ImageAsset;
        $imageAsset->userID = $user->id;
        $imageAsset->assetTypeID = 1;
        $imageAsset->ImageName = $validatedData['ImageName'];
        $imageAsset->ImageDescription = $request->input('ImageDescription');
        $imageAsset->Location = $imagePath;
        $imageAsset->Price = $validatedData['Price'];
        $imageAsset->ImageSize = $request->file('imageFile')->getSize();

        // Process watermarking
        if ($watermarkPath) {
            $image = Image::make(public_path('storage/' . $imagePath));
            $watermark = Image::make(public_path('storage/' . $watermarkPath));

            // Fit the watermark to the dimensions of the image
            $watermark->fit($image->width(), $image->height());

            // Insert the watermark onto the image
            $image->insert($watermark, 'center');

            // Save the watermarked image
            $watermarkedDirectory = 'public/watermarked/';
            $watermarkedImagePath = $validatedData['ImageName'] . '-watermarked.jpg'; // Assuming you want to save it as JPG
            $image->save(storage_path('app/' . $watermarkedDirectory . $watermarkedImagePath));

            // Update the image asset record with the path to the watermarked image
            $imageAsset->watermarkedImage = $watermarkedDirectory . $watermarkedImagePath;
        }

        $imageAsset->save();

        $selectedCategories = $request->input('category_ids', []);
        $imageAsset->categories()->attach($selectedCategories);

        return redirect()->route('image.show', $imageAsset->id)
            ->with('success', 'ImageAsset created successfully!');
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
}
