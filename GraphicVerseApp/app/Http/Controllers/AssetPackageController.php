<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Package;
use Illuminate\Http\Request;

class AssetPackageController extends Controller
{

    public function index()
    {

        $packages = Package::all();
        return view('asset.index', compact('packages'));
    }
    public function show($id)
    {
        $package = Package::with('assets')->findOrFail($id);
        $assets = $package->assets;
    
        return view('asset.show', compact('package', 'assets'));
    }
    public function create()
    {

        $user = auth()->user();
        $packages = $user->packages;

        return view('asset.create', compact('packages'));
    }



    public function store(Request $request)
    {
        $user = auth()->user();

        $previewFile = $request->file('preview');
        $originalFileName = $previewFile->getClientOriginalName();
        
        $imagePath = $previewFile->storeAs('public/preview', $originalFileName);
        
        $package = new Package([
            'PackageName' => $request['PackageName'],
            'Description' => $request['Description'],
            'preview' => $originalFileName, // Use the original filename
            'Location' => $imagePath, // Store the file in Laravel storage
            'UserID' => $user->id,
        ]);
        
        // Save the package to the database

        $package->save();


        // Create an array to store asset IDs associated with this upload
        $uploadedAssetIds = [];

        // Loop through uploaded files
        foreach ($request->file('asset') as $asset) {
            // Store each image in the storage/app/public directory
            $path = $asset->store('public/assets');

            // Create an Asset record in the database
            $asset = new Asset([

                'AssetName' => $asset->getClientOriginalName(),
                'FileType' => $asset->getClientOriginalExtension(),
                'FileSize' => $asset->getSize(),
                'Location' => $path,    
                'UserID' => $user->id,
                'PackageID' => $package->id, // Set the 'PackageID' to associate the asset with the new package
            ]);

            // Save the Asset record
            // dd($asset);
            $asset->save();

            // Add the asset ID to the uploadedAssetIds array
            $uploadedAssetIds[] = $asset->id;
        }
    

      //  Redirect back with success message and the uploaded asset IDs
        return redirect()->back()->with([
            'success' => 'Images uploaded successfully',
            'uploadedAssetIds' => $uploadedAssetIds,
        ]);

    }
}
