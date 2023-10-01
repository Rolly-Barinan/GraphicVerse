<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Package;
use Illuminate\Http\Request;

class AssetPackageController extends Controller
{

    public function index()
    {
        $packages = Package::all(); // Retrieve all packages from the database
    
        return view('asset.index', compact('packages'));
    }
    public function create()
    {

        $user = auth()->user();
        $packages = $user->packages;

        return view('asset.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user

        // Determine the current package (you might have a different way of handling this)
        $package = new Package([

            'PackageName' => 'New Package', // You can set a default package name
            'Description' => 'Description of the new package', // Add a default description if needed
            'UserID' => $user->id,
            'preview' => 'default_preview_value', // Set a default value for 'preview'
            'Location' => 'default_location_value', // Set a default value for 'Location'
        ]);
    
        $package->save();
    
        // Create an array to store asset IDs associated with this upload
        $uploadedAssetIds = [];
    
        // Loop through uploaded files
        foreach ($request->file('images') as $image) {
            // Store each image in the storage/app/public directory
            $path = $image->store('public/assets');
    
            // Create an Asset record in the database
            $asset = new Asset([
                'AssetName' => $image->getClientOriginalName(),
                'FileType' => $image->getClientOriginalExtension(),
                'FileSize' => $image->getSize(),
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
    
        // Redirect back with success message and the uploaded asset IDs
        return redirect()->back()->with([
            'success' => 'Images uploaded successfully',
            'uploadedAssetIds' => $uploadedAssetIds,
        ]);
    }
}
