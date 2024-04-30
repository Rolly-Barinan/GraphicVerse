<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Categories;
use App\Models\ImageAsset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Http;

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
        // Validate the incoming request
        $request->validate([
            'ImageName' => 'required',
            'imageFile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
        ]);

        // Create folders if they don't exist
        $publicPath = 'public/';
        $artPath = $publicPath . 'arts/';
        $watermarkPath = $publicPath . 'watermarked/';
        Storage::makeDirectory($artPath, 0777, true, true);
        Storage::makeDirectory($watermarkPath, 0777, true, true);

        // Store the image file
        $imageName = $request->ImageName;
        $imageFile = $request->file('imageFile');
        $imagePath = $artPath . $imageName . '.' . $imageFile->getClientOriginalExtension();
        Storage::putFileAs($publicPath . 'arts', $imageFile, $imageName . '.' . $imageFile->getClientOriginalExtension());

        // Check for inappropriate content in the image file
        $contentDetection = $this->detectInappropriateContent($imageFile);

        // Check if watermark file is uploaded and check for inappropriate content
        $watermarkedImage = null;
        if ($request->hasFile('watermarkFile')) {
            $watermarkFile = $request->file('watermarkFile');
            $watermarkPath = $watermarkPath . 'watermarked_' . $imageName . '.' . $watermarkFile->getClientOriginalExtension();

            // Load main image and watermark image
            $mainImage = Image::make(storage_path('app/' . $imagePath));
            $watermark = Image::make($watermarkFile);

            // Calculate scaling factor for watermark to fit image
            $scaleFactor = min($mainImage->width() / $watermark->width(), $mainImage->height() / $watermark->height());

            // Resize watermark to fit image
            $watermark->resize($watermark->width() * $scaleFactor, $watermark->height() * $scaleFactor);

            // Calculate watermark position
            $watermarkPositionX = ($mainImage->width() - $watermark->width()) / 2;
            $watermarkPositionY = ($mainImage->height() - $watermark->height()) / 2;

            // Merge watermark with main image
            $mainImage->insert($watermark, 'top-left', $watermarkPositionX, $watermarkPositionY);

            // Save watermarked image
            $mainImage->save(storage_path('app/' . $watermarkPath));

            // Check for inappropriate content in the watermark file
            $watermarkDetection = $this->detectInappropriateContent($watermarkFile);

            // Merge detection results from both files
            $contentDetection['nsfwDetected'] = $contentDetection['nsfwDetected'] || $watermarkDetection['nsfwDetected'];
            $contentDetection['aiDetected'] = $contentDetection['aiDetected'] || $watermarkDetection['aiDetected'];

            $watermarkedImage = $watermarkPath;
        }

        // If inappropriate content is detected, handle it accordingly
        if ($contentDetection['nsfwDetected'] || $contentDetection['aiDetected']) {
            // Delete uploaded files and return with an error message
            Storage::delete([$imagePath, $watermarkPath]);
            
            $errorMessage = '';
            if ($contentDetection['nsfwDetected']) {
                $errorMessage = 'Inappropriate content detected in the uploaded files. Please upload appropriate content.';
            } elseif ($contentDetection['aiDetected']) {
                $errorMessage = 'AI-generated content detected in the uploaded files. Please upload original content.';
            }
            
            return redirect()->back()->with('error', $errorMessage);
        }

        // Save data to database
        $imageAsset = new ImageAsset();
        $imageAsset->userID = auth()->id(); // Assuming you're using authentication
        $imageAsset->assetTypeID = 1; // Assuming asset type ID for 2D images is 1, adjust accordingly
        $imageAsset->ImageName = $request->ImageName;
        $imageAsset->ImageDescription = $request->ImageDescription;
        $imageAsset->Location = $imagePath;
        $imageAsset->Price = $request->Price;
        $imageAsset->ImageSize = $imageFile->getSize();
        $imageAsset->watermarkedImage = $watermarkedImage;
        $imageAsset->save();

        // Attach categories to the ImageAsset
        $imageAsset->categories()->attach($request->input('category_ids', []));

        // Redirect to the image create page with a success message
        return redirect()->back()->with('success', 'Artwork uploaded successfully!');
    }

    // Function to detect inappropriate content using Sightengine API
    private function detectInappropriateContent($file)
    {
        $params = [
            'models' => 'nudity-2.0, genai', // Specify the models you want to use for detection
            'api_user' => env('SIGHTENGINE_API_KEY'),
            'api_secret' => env('SIGHTENGINE_API_SECRET'),
        ];

        $response = Http::attach(
            'media',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post('https://api.sightengine.com/1.0/check.json', $params);
        
        $nsfwDetected = $response->successful() && (
            // Check for NSFW content
            $response['nudity']['sexual_activity'] > 0.5 || 
            $response['nudity']['erotica'] > 0.5 || 
            $response['nudity']['suggestive'] > 0.5 || 
            $response['nudity']['sexual_display'] > 0.5 || 
            $response['nudity']['sextoy'] > 0.5 || 
            $response['nudity']['suggestive_classes']['cleavage'] > 0.5 || 
            $response['nudity']['suggestive_classes']['lingerie'] > 0.5 || 
            $response['nudity']['suggestive_classes']['other'] > 0.5 || 
            $response['nudity']['suggestive_classes']['miniskirt'] > 0.5 || 
            $response['nudity']['suggestive_classes']['bikini'] > 0.5 || 
            $response['nudity']['suggestive_classes']['male_chest_categories']['very_revealing'] > 0.5 || 
            $response['nudity']['suggestive_classes']['male_chest_categories']['slightly_revealing'] > 0.5 || 
            $response['nudity']['suggestive_classes']['male_chest_categories']['revealing'] > 0.5 || 
            $response['nudity']['suggestive_classes']['cleavage_categories']['very_revealing'] > 0.5 || 
            $response['nudity']['suggestive_classes']['cleavage_categories']['revealing'] > 0.5 || 
            $response['nudity']['suggestive_classes']['male_underwear'] > 0.5 || 
            $response['nudity']['suggestive_classes']['male_chest'] > 0.5
        );

        $aiDetected = $response->successful() && (
            // Check for AI-generated content
            $response['type']['ai_generated'] > 0.90
        );
        
        return ['nsfwDetected' => $nsfwDetected, 'aiDetected' => $aiDetected];
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
                    $query->orderBy('ImageName');
                    break;
                case 'name_desc':
                    $query->orderByDesc('ImageName');
                    break;
                case 'price_asc':
                    $query->orderBy('Price');
                    break;
                case 'price_desc':
                    $query->orderByDesc('Price');
                    break;
                case 'username_asc':
                    $query->leftJoin('users', 'image_assets.UserID', '=', 'users.id')
                        ->orderBy('users.username');
                    break;
                case 'username_desc':
                    $query->leftJoin('users', 'image_assets.UserID', '=', 'users.id')
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
