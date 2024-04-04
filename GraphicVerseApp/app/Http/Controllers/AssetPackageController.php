<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Categories;
use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        $assetTypes = AssetType::all();
        $categories = Categories::all();

        return view('asset.edit', compact('package', 'assetTypes', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $request->validate([
            'asset_type_id' => 'required',
            'PackageName' => 'required',
            'Description' => 'required',
            'Price' => 'nullable|numeric',
        ]);

        $package->asset_type_id = $request->input('asset_type_id');
        $package->PackageName  = $request->input('PackageName');
        $package->Description = $request->input('Description');
        $package->Price = $request->input('Price');

        if ($request->hasFile('preview')) {
            $previewPath = $request->file('preview')->store('previews', 'public');
            $package->preview = $previewPath;
        }
        $package->categories()->sync($request->input('category_ids', []));
        $package->save();

        return redirect()->route('asset.edit', $package->id)->with('success', 'Package updated successfully.');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'PackageName' => 'required',
            'Description' => 'required',
            'preview' => 'required|image',
            'asset' => 'required|array',
            'asset.*' => 'required|file',
            'asset_type_id' => 'required',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => Rule::exists('categories', 'id'),
        ];

        $messages = [
            'category_ids.min' => 'Please select at least one category.',
            'asset.*.required' => 'Each asset file is required.',
            'asset.*.mimes' => 'The following files do not have valid extensions:'
        ];

        if ($request->input('asset_type_id') == 1) {
            $rules['asset.*'] .= '|mimes:jpeg,png,jpg, gif,bmp,tiff,svg,psd,ai,eps ,webp';
            $messages['asset.*.mimes'] = 'Only JPEG and PNG files are allowed for this asset type.';
        } elseif ($request->input('asset_type_id') == 2) {
            $rules['asset.*'] .= '|mimes:fbx,obj,bin, blend,dae ,3ds,ply,x3d,gltf ,glb ,igs  ,iges ';
            $messages['asset.*.mimes'] = 'Only FBX, OBJ, and BIN files are allowed for this asset type.';
        } elseif ($request->input('asset_type_id') == 3) {
            $rules['asset.*'] .= '|mimes:wav,mp3,opus,m4a,alac,aiff,wma,flac,ogg,aac';
            $messages['asset.*.mimes'] = 'Only FBX, OBJ, and BIN files are allowed for this asset type.';
        }
        $request->validate($rules, $messages);
        $previewFile = $request->file('preview');
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
        $zipFileName = 'package_' . $package->id . '.zip';

        $zip = new ZipArchive;

        if ($zip->open(storage_path('app/' . $zipFileName), ZipArchive::CREATE) === TRUE) {
            $previewFilePath = storage_path('app/' . $package->Location);
            $previewFileName = $package->PackageName . '.jpg';
            $zip->addFile($previewFilePath, $previewFileName);

            $assets = $package->assets;
            foreach ($assets as $asset) {
                $assetFilePath = storage_path('app/' . $asset->Location);
                $assetFileName = 'assets/' . $asset->AssetName;
                $zip->addFile($assetFilePath, $assetFileName);
            }
            $zip->close();
        }

        $headers = [
            'Content-Type' => 'application/zip',
        ];

        return response()->download(storage_path('app/' . $zipFileName), $zipFileName, $headers);
    }

    public function destroy(Package $package)
    {
        $previewPath = $package->Location;
        foreach ($package->assets as $asset) {
            $filePath = $asset->Location;

            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            PackageCategory::where('package_id', $package->id)->delete();
            $asset->delete();
        }
        if (Storage::exists($previewPath)) {
            Storage::delete($previewPath);
        }
        $package->delete();
        return redirect('/')->with('success', 'Package and associated files deleted successfully.');
    }
}
