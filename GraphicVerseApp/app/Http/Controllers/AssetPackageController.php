<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;


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

        $package->save();

        $uploadedAssetIds = [];

        foreach ($request->file('asset') as $asset) {
            $path = $asset->store('public/assets');

            $asset = new Asset([
                'AssetName' => $asset->getClientOriginalName(),
                'FileType' => $asset->getClientOriginalExtension(),
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

        // Create a temporary directory to store the files before zipping
        $tempDir = storage_path('app/temp_zip');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Copy the preview image to the temporary directory
        $previewFilePath = storage_path('app/' . $package->Location);
        $previewFileName = $package->PackageName . '.jpg';
        copy($previewFilePath, $tempDir . '/' . $previewFileName);

        // Find and copy associated assets to the temporary directory
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
