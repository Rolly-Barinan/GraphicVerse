<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Categories;
use Intervention\Image\Facades\Image;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use ZipArchive;
use Illuminate\Validation\Rule;

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
    public function display3d($id)

    {
        $package = Package::with('assets')->findOrFail($id);
        $assets = $package->assets;

        return view('asset.display3d', compact('package', 'assets'));
    }

    public function showWatermarked($id)
    {
        $asset = Asset::findOrFail($id);

        // Get the image contents using the Storage facade
        $path = storage_path('app/' . $asset->Location);
        // dd($imageContent);


        // Create an image instance from the contents
        $image = Image::make($path);

        // Provide the correct path to your watermark image
        $watermark =  Image::make(public_path('svg/watermark.png'));
        // dd($watermark);
        $watermark->resize($image->width(), $image->height());
        $image->insert($watermark, 'center');

        $watermarkedDirectory = storage_path('app/public/watermarked/');
        $watermarkedImagePath = $watermarkedDirectory . $asset->AssetName . '-watermarked.png';

        $image->save($watermarkedImagePath);
        // dd($image);
        // Output the watermarked image to the browser
        return response()->download($watermarkedImagePath);
    }
    

    public function create()
    {
        if (Auth::check()) {
            $user = auth()->user();
            $packages = $user->packages;
            $assetTypes = AssetType::all(); // Fetch asset types from the database
            $categories = Categories::all(); // Fetch categories from the database
            return view('asset.create', compact('packages', 'assetTypes', 'categories'));
        }
        return redirect()->route('login')->with('error', 'Please log in to create a package.');
    }

    public function store(Request $request)
    {
        if (Auth::check()) {

            $user = auth()->user();
            $rules = [
                'PackageName' => 'required',
                'Description' => 'required',
                'preview' => 'required|image',
                'asset' => 'required|array',
                'asset.*' => 'required|file|mimes:jpeg,png,txt,bin, fbx',
                'asset_type_id' => 'required',
                'category_ids' => 'required|array',
                'category_ids.*' => Rule::exists('categories', 'id'),
            ];

            $request->validate($rules);
            $previewFile = $request->file('preview');
            $originalFileName = $previewFile->getClientOriginalName();
            $imagePath = $previewFile->storeAs('public/preview', $originalFileName);
            $package = new Package([
                'PackageName' => $request['PackageName'],
                'Description' => $request['Description'],
                'preview' => $originalFileName,
                'Location' => $imagePath,
                'UserID' => $user->id,
                'asset_type_id' => $request['asset_type_id'], // Store selected asset type
                'Price' => $request['Price'], // Store the price
            ]);
            $package->save();
            // Process and save categories
            $selectedCategories = $request->input('category_ids', []);
            $package->categories()->attach($selectedCategories);
            // Process and save assets
            $uploadedAssetIds = [];
            foreach ($request->file('asset') as $asset) {
                $extension = $asset->getClientOriginalExtension();

                // Generate a unique filename for the FBX file
                $uniqueFilename = time() . '-' . Str::random(10) . '.' . $extension;
                $path = $asset->storeAs('public/assets', $uniqueFilename);

                $asset = new Asset([
                    'AssetName' => $asset->getClientOriginalName(),
                    'FileType' => $extension,
                    'FileSize' => $asset->getSize(),
                    'Location' => $path,
                    'UserID' => $user->id,
                    'PackageID' => $package->id,
                ]);

                $asset->save();

                $uploadedAssetIds[] = $asset->id; // Store the IDs of uploaded assets
            }

            // Redirect with success message and uploadedAssetIds
            return redirect()->back()->with([
                'success' => 'Images uploaded successfully',
                'uploadedAssetIds' => $uploadedAssetIds, // Include the array of asset IDs
            ]);
        }
        return redirect()->route('login')->with('error', 'Please log in to create a package.');
    }



    public function download($id)
    {

        $package = Package::findOrFail($id);


        $tempDir = storage_path('app/temp_zip');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $previewFilePath = storage_path('app/' . $package->Location);
        $previewFileName = $package->PackageName . '.jpg';
        copy($previewFilePath, $tempDir . '/' . $previewFileName);

        $assets = $package->assets;
        foreach ($assets as $asset) {
            $assetFilePath = storage_path('app/' . $asset->Location);
            $assetFileName = $asset->AssetName;
            copy($assetFilePath, $tempDir . '/' . $assetFileName);
        }
        $zipFileName = 'package_' . $package->id . '.zip';
        $zip = new ZipArchive;
        if ($zip->open($tempDir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {

            $zip->addFile($tempDir . '/' . $previewFileName, $previewFileName);

            foreach ($assets as $asset) {
                $zip->addFile($tempDir . '/' . $asset->AssetName, 'assets/' . $asset->AssetName);
            }

            $zip->close();
        }

        $headers = [
            'Content-Type' => 'application/zip',
        ];

        return response()->download($tempDir . '/' . $zipFileName, $zipFileName, $headers);
    }
}