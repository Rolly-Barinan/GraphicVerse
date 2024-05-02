<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Categories;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\Tag;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Validation\Rule;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;

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
        $recommendedTags = Tag::all();
        $userTeams = $user->teams;

        return view('asset.create', compact('packages', 'assetTypes', 'categories', 'recommendedTags', 'userTeams'));
    }
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        $assetTypes = AssetType::all();
        $categories = Categories::all();
        $teams = Team::all();

        return view('asset.edit', compact('package', 'assetTypes', 'categories', 'teams'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $request->validate([
            'PackageName' => 'required',
            'Description' => 'required',
            'Price' => 'nullable|numeric',
        ]);

        // $package->asset_type_id = $request->input('asset_type_id');
        $package->team_id = $request['team_id'];
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
        $filename = $previewFile->hashName();
        $imagePath = $previewFile->storeAs('public/preview', $filename);
        
        $customTags = $request->input('customTags');
        $tagNames = explode(',', $customTags);

        $package = new Package([
            'PackageName' => $request['PackageName'],
            'Description' => $request['Description'],
            'preview' => $filename,
            'Location' => $imagePath,
            'UserID' => $user->id,
            'team_id' => $request['team_id'],
            'asset_type_id' => $request['asset_type_id'],
            'Price' => $request['Price'],
        ]);

        $package->team_id = $request['team_id'];
        $package->save();

        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if (!empty($tagName)) {
                // Check if tag already exists in the database
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                // Associate tag with the package
                $package->tags()->attach($tag);
            }
        }

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

        // Initialize a flag to track if NSFW content is detected
        $nsfwDetected = false;
        $aiDetected = false;

        // Iterate through each uploaded asset file
        foreach ($request->file('asset') as $asset) {
            // Initialize parameters for Sightengine API request
            $params = [
                'models' => 'nudity-2.0, genai', // Specify the models you want to use for detection
                'api_user' => env('SIGHTENGINE_API_KEY'),
                'api_secret' => env('SIGHTENGINE_API_SECRET'),
            ];

            // Make a POST request to Sightengine API with multipart form data
            $response = Http::attach(
                'media',
                file_get_contents($asset->getRealPath()),
                $asset->getClientOriginalName()
            )->post('https://api.sightengine.com/1.0/check.json', $params);

            // Check if the request was successful and NSFW content is detected
            if (
                $response->successful() && $response['nudity']['sexual_activity'] +
                $response['nudity']['erotica'] +
                $response['nudity']['suggestive'] +
                $response['nudity']['sexual_display'] +
                $response['nudity']['sextoy'] +
                $response['nudity']['suggestive_classes']['cleavage'] +
                $response['nudity']['suggestive_classes']['lingerie'] +
                $response['nudity']['suggestive_classes']['other'] +
                $response['nudity']['suggestive_classes']['miniskirt'] +
                $response['nudity']['suggestive_classes']['bikini'] +
                $response['nudity']['suggestive_classes']['male_chest_categories']['very_revealing'] +
                $response['nudity']['suggestive_classes']['male_chest_categories']['slightly_revealing'] +
                $response['nudity']['suggestive_classes']['male_chest_categories']['revealing'] +
                $response['nudity']['suggestive_classes']['cleavage_categories']['very_revealing'] +
                $response['nudity']['suggestive_classes']['cleavage_categories']['revealing'] +
                $response['nudity']['suggestive_classes']['male_underwear'] +
                $response['nudity']['suggestive_classes']['male_chest'] > 0.5
            ) {
                // Handle NSFW content (you can customize this part based on your application's requirements)
                $nsfwDetected = true;

                foreach ($package->assets as $asset) {

                    $filePath = $asset->Location;
                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                }
                PackageCategory::where('package_id', $package->id)->delete();
                $asset->delete();
                $previewPath = $package->Location;
                if (Storage::exists($previewPath)) {

                    Storage::delete($previewPath);
                }
                $package->delete();
                return redirect()->back()->with('error', 'NSFW content detected in one of the asset files! Please upload safe content.');
            } elseif ($response->successful() && $response['type']['ai_generated'] > 0.90) {
                $aiDetected = true;

                foreach ($package->assets as $asset) {

                    $filePath = $asset->Location;
                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                }
                PackageCategory::where('package_id', $package->id)->delete();
                $asset->delete();
                $previewPath = $package->Location;
                if (Storage::exists($previewPath)) {

                    Storage::delete($previewPath);
                }
                $package->delete();
                return redirect()->back()->with('error', 'AI Generated content detected in one of the asset files! Please upload proper content.');
            }

            // Check if NSFW content is detected in any of the assets
            if ($nsfwDetected) {
                // Handle NSFW content (you can customize this part based on your application's requirements)
                return redirect()->back()->with('error', 'NSFW content detected in one of the asset files! Please upload safe content.');
            } elseif ($aiDetected) {
                return redirect()->back()->with('error', 'AI Generated content detected in one of the asset files! Please upload proper content.');
            } else {
                $params = [
                    'models' => 'nudity-2.0, genai', // Specify the models you want to use for detection
                    'api_user' => env('SIGHTENGINE_API_KEY'),
                    'api_secret' => env('SIGHTENGINE_API_SECRET'),
                ];
    
                // Make a POST request to Sightengine API with multipart form data
                $response = Http::attach(
                    'media',
                    file_get_contents($previewFile->getRealPath()),
                    $previewFile->getClientOriginalName()
                )->post('https://api.sightengine.com/1.0/check.json', $params);
    
                // Check if the request was successful and NSFW content is detected
                if (
                    $response->successful() && $response['nudity']['sexual_activity'] +
                    $response['nudity']['erotica'] +
                    $response['nudity']['suggestive'] +
                    $response['nudity']['sexual_display'] +
                    $response['nudity']['sextoy'] +
                    $response['nudity']['suggestive_classes']['cleavage'] +
                    $response['nudity']['suggestive_classes']['lingerie'] +
                    $response['nudity']['suggestive_classes']['other'] +
                    $response['nudity']['suggestive_classes']['miniskirt'] +
                    $response['nudity']['suggestive_classes']['bikini'] +
                    $response['nudity']['suggestive_classes']['male_chest_categories']['very_revealing'] +
                    $response['nudity']['suggestive_classes']['male_chest_categories']['slightly_revealing'] +
                    $response['nudity']['suggestive_classes']['male_chest_categories']['revealing'] +
                    $response['nudity']['suggestive_classes']['cleavage_categories']['very_revealing'] +
                    $response['nudity']['suggestive_classes']['cleavage_categories']['revealing'] +
                    $response['nudity']['suggestive_classes']['male_underwear'] +
                    $response['nudity']['suggestive_classes']['male_chest'] > 0.5
                ) {
                    // Handle NSFW content (you can customize this part based on your application's requirements)
                    $nsfwDetected = true;
    
                    foreach ($package->assets as $asset) {
    
                        $filePath = $asset->Location;
                        if (Storage::exists($filePath)) {
                            Storage::delete($filePath);
                        }
                    }
                    PackageCategory::where('package_id', $package->id)->delete();
                    $asset->delete();
                    $previewPath = $package->Location;
                    if (Storage::exists($previewPath)) {
    
                        Storage::delete($previewPath);
                    }
                    $package->delete();
                    return redirect()->back()->with('error', 'NSFW content detected in the preview! Please upload safe content.');
                } elseif ($response->successful() && $response['type']['ai_generated'] > 0.90) {
                    $aiDetected = true;
    
                    foreach ($package->assets as $asset) {
    
                        $filePath = $asset->Location;
                        if (Storage::exists($filePath)) {
                            Storage::delete($filePath);
                        }
                    }
                    PackageCategory::where('package_id', $package->id)->delete();
                    $asset->delete();
                    $previewPath = $package->Location;
                    if (Storage::exists($previewPath)) {
    
                        Storage::delete($previewPath);
                    }
                    $package->delete();
                    return redirect()->back()->with('error', 'AI Generated content detected in the preview! Please upload proper content.');
                } else {
                    // If no inappropriate content is detected in the preview file, proceed with the success message
                    return redirect()->back()->with([
                        'success' => 'Package uploaded successfully',
                        'uploadedAssetIds' => $uploadedAssetIds,
                    ]);
                }
            }
        }
    }

    public function download($id)
    {
        $package = Package::findOrFail($id);
        $zipFileName = 'package_' . $package->id . '.zip';
        // Create a temporary file in memory
        $zipFile = tempnam(sys_get_temp_dir(), $zipFileName);
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Add the preview file to the zip archive
            $previewFilePath = storage_path('app/' . $package->Location);
            $previewFileName = $package->PackageName . '.jpg';
            $zip->addFile($previewFilePath, $previewFileName);
            // Add the assets to the zip archive
            $assets = $package->assets;
            foreach ($assets as $asset) {
                $assetFilePath = storage_path('app/' . $asset->Location);
                $assetFileName = 'assets/' . $asset->AssetName;
                $zip->addFile($assetFilePath, $assetFileName);
            }
            $zip->close();
            // Set headers for the response
            $headers = [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
            ];
            // Stream the zip file for download and delete the temporary file afterwards
            return response()->streamDownload(function () use ($zipFile) {
                readfile($zipFile);
                unlink($zipFile); // Delete the temporary file after streaming
            }, $zipFileName, $headers);
        } else {
            // Handle case where zip archive could not be created
            return response()->json(['error' => 'Unable to create the zip archive'], 500);
        }
    }

    public function destroy(Package $package)
    {
        foreach ($package->assets as $asset) {

            $filePath = $asset->Location;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }
        PackageCategory::where('package_id', $package->id)->delete();
        $asset->delete();
        $previewPath = $package->Location;
        if (Storage::exists($previewPath)) {

            Storage::delete($previewPath);
        }
        $package->delete();
        return redirect('/')->with('success', 'Package and associated files deleted successfully.');
    }

   
}
