<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Package;
use App\Models\ImageAsset;
use Illuminate\Http\Request;
use App\Models\Categories;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $packages = Package::where('PackageName', 'like', "%$query%")->paginate(12);
        $images = ImageAsset::where('ImageName', 'like', "%$query%")->paginate(12);
        // Retrieve categories
        $categories = Categories::all(); // Assuming Category is the model for your categories

        // Combine packages and images
        $searchResults = $packages->merge($images);

        // Paginate the sorted results
        $perPage = 12;
        $currentPage = $request->input('page') ?? 1;
        $pagedSearchResults = new LengthAwarePaginator(
            $searchResults->forPage($currentPage, $perPage),
            $searchResults->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('search', compact('packages', 'images', 'pagedSearchResults', 'query', 'categories'));
    }


    public function filteredSearchResults(Request $request)
    {
        $query = $request->input('q');

        // Fetch categories from the database
        $categories = Categories::all();

        $categoryIds = $request->input('categories');
        $priceRanges = $request->input('price_range');
        $authorSearch = $request->input('search');
        $sortBy = $request->input('sort');

        $queryBuilder = Package::where('PackageName', 'like', "%$query%");

        // Apply category filter
        if (!empty($categoryIds)) {
            $queryBuilder->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Apply price range filter
        if (!empty($priceRanges)) {
            $queryBuilder->where(function ($q) use ($priceRanges) {
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

        // Apply author search filter
        if (!empty($authorSearch)) {
            $queryBuilder->whereHas('user', function ($q) use ($authorSearch) {
                $q->where('username', 'like', "%$authorSearch%");
            });
        }

        // Apply sorting
        switch ($sortBy) {
            case 'name_asc':
                $queryBuilder->orderBy('PackageName', 'asc');
                break;
            case 'name_desc':
                $queryBuilder->orderBy('PackageName', 'desc');
                break;
            case 'price_asc':
                $queryBuilder->orderBy('Price', 'asc');
                break;
            case 'price_desc':
                $queryBuilder->orderBy('Price', 'desc');
                break;
            case 'username_asc':
                $queryBuilder->leftJoin('users', 'packages.UserID', '=', 'users.id')
                ->orderBy('users.username');
                break;
            case 'username_desc':
                $queryBuilder->leftJoin('users', 'packages.UserID', '=', 'users.id')
                ->orderByDesc('users.username');
                break;
            // Add more sorting options as needed
        }

        // Fetch images based on the same filters and sorting
        $imagesQuery = ImageAsset::where('ImageName', 'like', "%$query%");

        // Apply category filter
        if (!empty($categoryIds)) {
            $imagesQuery->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Apply price range filter
        if (!empty($priceRanges)) {
            $imagesQuery->where(function ($q) use ($priceRanges) {
                // Same logic as with packages
            });
        }

        // Apply author search filter
        if (!empty($authorSearch)) {
            $imagesQuery->whereHas('user', function ($q) use ($authorSearch) {
                $q->where('username', 'like', "%$authorSearch%");
            });
        }
        
        $packages = $queryBuilder->paginate(12);
        $images = $imagesQuery->paginate(12);

        // Combine packages and images
        $searchResults = $queryBuilder->get()->merge($imagesQuery->get());

        // Apply sorting
        switch ($request->input('sort')) {
            case 'name_asc':
                $searchResults = $searchResults->sortBy(function ($item) {
                    return $item instanceof Package ? $item->PackageName : $item->ImageName;
                });
                break;
            case 'name_desc':
                $searchResults = $searchResults->sortByDesc(function ($item) {
                    return $item instanceof Package ? $item->PackageName : $item->ImageName;
                });
                break;
            case 'price_asc':
                $searchResults = $searchResults->sortBy('Price');
                break;
            case 'price_desc':
                $searchResults = $searchResults->sortByDesc('Price');
                break;
            case 'username_asc':
                $searchResults = $searchResults->sortBy(function ($item) {
                    return $item instanceof Package ? $item->user->username : $item->user->username;
                });
                break;
            case 'username_desc':
                $searchResults = $searchResults->sortByDesc(function ($item) {
                    return $item instanceof Package ? $item->user->username : $item->user->username;
                });
                break;
            // Add more sorting options as needed
        }

        // Paginate the sorted results
        $perPage = 12;
        $currentPage = $request->input('page') ?? 1;
        $pagedSearchResults = new LengthAwarePaginator(
            $searchResults->forPage($currentPage, $perPage),
            $searchResults->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Pass categories to the view
        return view('search', compact('packages', 'images', 'pagedSearchResults', 'searchResults', 'query', 'categories', 'categoryIds', 'priceRanges'));
    }

}
