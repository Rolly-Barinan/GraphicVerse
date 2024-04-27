<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Categories;
use App\Models\ImageAsset;
use App\Models\ImageCategory;
use App\Models\User;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageAssetController extends Controller
{

    public function index(Request $request)
    {
        $query = ImageAsset::query()->whereHas('assetType', function ($q) {
            $q->where('asset_type', '2D');
        });

        $images = $query->paginate(12)->appends(request()->except('page'));

        $categories = Categories::all();
        return view('image.index', compact('images', 'categories'));
    }

    public function create()
    {
        $categories = Categories::all();
        $assetTypes = AssetType::all();
        return view('image.create', compact('assetTypes', 'categories'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'ImageName' => 'required',
            'Price' => 'required',
            'imageFile' => 'required|image|mimes:jpeg,png,jpg,gif',
            'watermarkFile' => 'image|mimes:jpeg,png,jpg,gif',
            'category_ids' => 'array',
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Store the original image file
        $imageFile = $request->file('imageFile');

        // Get the original file name
        $originalFileName = $imageFile->getClientOriginalName();

        // Create an instance of Intervention Image
        $image = Image::make($imageFile);
        $origImage = Image::make($imageFile);
        // Check if a watermark file is provided
        if ($request->hasFile('watermarkFile')) {
            $watermarkFile = $request->file('watermarkFile');
            $watermark = Image::make($watermarkFile);

            // Fit the watermark to the dimensions of the original image
            $watermark->fit($image->width(), $image->height());

            // Insert the watermark onto the original image
            $image->insert($watermark, 'center');
        }

        // Define directory for storing images
        $directory = 'public/images/';
        $watermarkDir = 'public/watermark/';

        // Ensure directories exist, create them if not
        File::ensureDirectoryExists(storage_path('app/public/' . $directory));
        File::ensureDirectoryExists(storage_path('app/public/' . $watermarkDir));

        // Generate a unique filename for the image
        $filename = $imageFile->hashName();

        try {
            // Save the image to the desired location
            $image->save(storage_path('app/' . $watermarkDir . $filename));
            $origImage->save(storage_path('app/' . $directory . $filename));
        } catch (\Exception $e) {
            // Log or handle the exception
            return back()->withInput()->withErrors(['error' => 'Failed to save image: ' . $e->getMessage()]);
        }

        // Create a new ImageAsset instance with the provided data
        $imageAsset = new ImageAsset([
            'userID' => $user->id,
            'assetTypeID' => 1,
            'ImageName' => $request->input('ImageName'),
            'ImageDescription' => $request->input('ImageDescription'),
            'Location' =>  $directory . $filename,
            'Price' => $request->input('Price'),
            'ImageSize' => $imageFile->getSize(),
            'watermarkedImage' =>   $watermarkDir . $filename,
        ]);

        // Save the ImageAsset instance to the database
        $imageAsset->save();

        // Attach categories to the ImageAsset
        $imageAsset->categories()->attach($request->input('category_ids', []));

        // Redirect to the image create page with a success message
        return redirect()->route('image.create')->with('success', 'ImageAsset created successfully!');
    }


    public function show($id)
    {
        $image = ImageAsset::findOrFail($id);
        $userId = $image->userID;
        $user = User::find($userId);
        $imageSize = $image->ImageSize / 1024;;
        return view('image.show', compact('image', 'user', 'imageSize'));
    }

    public function download($id)
    {
        $image = ImageAsset::findOrFail($id);
        $filePath = storage_path('app/public/' . $image->Location);

        if (!file_exists($filePath)) {
            abort(404);
        }
        $fileName = basename($image->Location);
        return response()->download($filePath, $fileName);
    }

    public function destroy(ImageAsset $image)
    {
 
        $origImage = $image->Location;
        $watermark = $image->watermarkedImage;
        if (Storage::exists($origImage)) {
            Storage::delete($origImage);
        }   
        if (Storage::exists($watermark)) {
            Storage::delete($watermark);
        }
        $image->delete();
        return redirect('/image')->with('success', 'Image asset deleted successfully.');
    }
    
    public function filterImage(Request $request)
    {
        $categoryIds = $request->input('categories');
        $priceRanges = $request->input('price_range');
        $searchQuery = $request->input('search'); // Retrieve the search query parameter

        $query = ImageAsset::query()->whereHas('assetType', function ($q) {
            $q->where('asset_type', '2D');
        });

        // Filter by category
        if (!is_array($categoryIds) || empty($categoryIds)) {
            $query->whereHas('categories');
        } else {
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Filter by price range
        if (is_array($priceRanges) && !empty($priceRanges)) {
            $query->where(function ($q) use ($priceRanges) {
                foreach ($priceRanges as $range) {
                    switch ($range) {
                        case 'free':
                            $q->orWhere('Price', '=', 0);
                            break;
                        case '1-100':
                            $q->orWhereBetween('Price', [1, 100]);
                            break;
                        case '101-500':
                            $q->orWhereBetween('Price', [101, 500]);
                            break;
                        case '501-1000':
                            $q->orWhereBetween('Price', [501, 1000]);
                            break;
                        case '1000+':
                            $q->orWhere('Price', '>', 1000);
                            break;
                    }
                }
            });
        }

        // Filter by author username (search query)
        if ($searchQuery) {
            $query->whereHas('user', function ($q) use ($searchQuery) {
                $q->where('username', 'like', '%' . $searchQuery . '%');
            });
        }

        // Apply sorting if a sort option is provided
        if ($request->has('sort')) {
            switch ($request->input('sort')) {
                case 'name_asc':
                    $query->orderBy('PackageName');
                    break;
                case 'name_desc':
                    $query->orderByDesc('PackageName');
                    break;
                case 'price_asc':
                    $query->orderBy('Price');
                    break;
                case 'price_desc':
                    $query->orderByDesc('Price');
                    break;
                case 'username_asc':
                    $query->leftJoin('users', 'packages.UserID', '=', 'users.id')
                        ->orderBy('users.username');
                    break;
                case 'username_desc':
                    $query->leftJoin('users', 'packages.UserID', '=', 'users.id')
                        ->orderByDesc('users.username');
                    break;
                default:
                    // Default sorting
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            // Default sorting
            $query->orderBy('created_at', 'desc');
        }

        // Paginate the results
        $images = $query->paginate(12)->appends(request()->except('page'));

        $categories = Categories::all();
        return view('image.index', compact('images', 'categories'));
    }
}
