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
        $user = auth()->user();

        // Validate the incoming request data

        // Handle the image upload and store it in the public directory or storage as per your configuration.
        $imagePath = $request->file('preview')->store('preview'); // You can configure the storage path as needed.

        // Create a new Package instance and fill it with the validated data


        $package = new Package([

            'PackageName' => $request['PackageName'],
            'Description' => $request['Description'],
            'preview' => $request->file('preview'),
            'Location' => $imagePath,
            'UserID' => $user->id,
            // Set the 'PackageID' to associate the asset with the new package
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
