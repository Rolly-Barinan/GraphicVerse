<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AudioController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::query()->whereHas('assetType', function ($q) {
            $q->where('asset_type', 'audio');
        });

        $packages = $query->paginate(8)->appends(request()->except('page'));
        // Ensure that paginator uses bootstrap styling
        Paginator::useBootstrap();

        $categories = Categories::all();
        return view('audio.index', compact('packages', 'categories'));
    }

    public function show($id)
    {
        $package = Package::with('assets')->findOrFail($id);
        $user = User::with('packages')->findOrFail($package->UserID);
        $assets = $package->assets;
        $totalSizeKB = 0;
        foreach ($assets as $asset) {
            $totalSizeKB += $asset->FileSize;
        }
        $totalSizeMB = $totalSizeKB / 1024;
        $fileTypes = $assets->pluck('FileType')->map(function ($type) {
            return '.' . strtolower($type);
        })->unique();

        return view('audio.show', compact('package', 'assets', 'totalSizeMB', 'fileTypes', 'user'));
    }

    public function filterPackages(Request $request)
    {
        $categoryIds = $request->input('categories');
        $searchQuery = $request->input('search'); // Retrieve the search query parameter
        $priceRanges = $request->input('price_range');

        $query = Package::query()->whereHas('assetType', function ($q) {
            $q->where('asset_type', 'audio');
        });

        // Filter by category
        if (!is_array($categoryIds) || empty($categoryIds)) {
            $query->whereHas('categories');
        } else {
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Filter by author username (search query)
        if ($searchQuery) {
            $query->whereHas('user', function ($q) use ($searchQuery) {
                $q->where('username', 'like', '%' . $searchQuery . '%');
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
        $packages = $query->paginate(8)->appends(request()->except('page'));
        // Ensure that paginator uses bootstrap styling
        Paginator::useBootstrap();

        $categories = Categories::all();
        return view('audio.index', compact('packages', 'categories'));
    }
}
