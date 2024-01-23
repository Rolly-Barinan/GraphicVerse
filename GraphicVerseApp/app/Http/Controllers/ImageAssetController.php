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
        //
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
        // Validate the form data
        $validatedData = $request->validate([
            'ImageName' => 'required',
           
            'Price' => 'required',
            'imageFile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
           
            'category_ids' => 'array', // Assuming category_ids is an array
        ]);
    
        // Get the current authenticated user using auth()
        $user = auth()->user();
        dd($user);
    
        // Upload and store the image
        $imagePath = $request->file('imageFile')->store('images', 'public');
    
        // Upload and store the watermark image
        $watermarkPath = $request->file('watermarkFile')->store('watermarks', 'public');
    
        // Calculate the image size
        $imageSize = $request->file('imageFile')->getSize();
    
        // Create a new ImageAsset instance
        $imageAsset = new ImageAsset;
        $imageAsset->userID = $user->id;
        $imageAsset->assetTypeID = 1; // Assuming a default asset type ID
        $imageAsset->ImageName = $validatedData['ImageName'];
        $imageAsset->ImageDescription = $request->input('ImageDescription');
        $imageAsset->Location = $imagePath; // Path to the uploaded image
        $imageAsset->Price = $validatedData['Price'];
        $imageAsset->ImageSize = $imageSize;
        $imageAsset->watermarkedImage = $watermarkPath; // Path to the watermark image

    dd($imageAsset);

    
        // Attach selected categories to the image asset
        if ($request->has('category_ids')) {
            $imageAsset->categories()->attach($validatedData['category_ids']);
        }
    
        // Redirect with success message
        return redirect()->route('image.show', $imageAsset->id)
            ->with('success', 'ImageAsset created successfully! Your additional success prompt here.');
    }
    



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
