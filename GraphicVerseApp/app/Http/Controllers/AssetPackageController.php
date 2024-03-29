<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Categories;
use App\Models\Package;
use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Validation\Rule;

class AssetPackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('asset.index', compact('packages'));
    }

    public function create()
    {
        $user = auth()->user();
        $packages = $user->packages;
        $assetTypes = AssetType::all();
        $categories = Categories::all();
        return view('asset.create', compact('packages', 'assetTypes', 'categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'PackageName' => 'required',
            'Description' => 'required',
            'preview' => 'required|image',
            'asset' => 'required|array',
            'asset.*' => 'required|file|mimes:jpeg,png,bin,wav, fbx',
            'asset_type_id' => 'required',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => Rule::exists('categories', 'id'),
        ];

        $messages = [
            'category_ids.min' => 'Please select at least one category.',
        ];
        $request->validate($rules, $messages);
        $selectedAssetType = AssetType::find($request->input('asset_type_id'));
        foreach ($request->file('asset') as $asset) {
            $extension = $asset->getClientOriginalExtension();
            if (($selectedAssetType->asset_type === '3D' && strtolower($extension) !== 'fbx') ||
                ($selectedAssetType->asset_type === '2D' && !in_array(strtolower($extension), ['jpeg', 'png', 'txt', 'bin']))
            ) {
                return redirect()->back()->with('error', 'Invalid file type for selected asset type.');
            }
        }
        $previewFile = $request->file('preview');

        if (!$previewFile->isValid() || !in_array($previewFile->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
            return redirect()->back()->with('error', 'Preview file must be an image (JPEG, PNG, GIF).');
        }

        $originalFileName = $previewFile->getClientOriginalName();
        $imagePath = $previewFile->storeAs('public/preview', $originalFileName);

        $package = new Package([
            'PackageName' => $request['PackageName'],
            'Description' => $request['Description'],
            'preview' => $originalFileName,
            'Location' => $imagePath,
            'UserID' => $user->id,
            'asset_type_id' => $request['asset_type_id'], 
            'Price' => $request['Price'], 
        ]);

        $package->save();

        $selectedCategories = $request->input('category_ids', []);
        $package->categories()->attach($selectedCategories);

        $uploadedAssetIds = [];

        foreach ($request->file('asset') as $asset) {
            $extension = $asset->getClientOriginalExtension();
           
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
            $uploadedAssetIds[] = $asset->id; 
        }
        return redirect()->back()->with([
            'success' => 'Images uploaded successfully',
            'uploadedAssetIds' => $uploadedAssetIds, 
        ]);
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
