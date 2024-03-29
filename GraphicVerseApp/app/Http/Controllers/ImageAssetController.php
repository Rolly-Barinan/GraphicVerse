<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Categories;
use App\Models\ImageAsset;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImageAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = ImageAsset::all();
        return view('image.index', compact('images'));     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categories::all();
        $assetTypes = AssetType::all();
        return view('image.create', compact('assetTypes', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $image = ImageAsset::findOrFail($id);
    
        return view('image.show', compact('image'));
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
