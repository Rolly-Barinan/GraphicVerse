<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Package;
use App\Models\ImageAsset;
use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Pagination\Paginator;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $packages = Package::where('PackageName', 'like', "%$query%")->paginate(8);
        $images = ImageAsset::where('ImageName', 'like', "%$query%")->paginate(8);
        // Retrieve categories
        $categories = Categories::all(); // Assuming Category is the model for your categories

        // Combine packages and images into a single collection
        $results = $packages->concat($images);

        // Paginate the sorted results
        $sortedResults = $this->paginateResults($results, 8);

        // Ensure that paginator uses bootstrap styling
        Paginator::useBootstrap();

        // Pass the combined results to the view
        return view('search', compact('sortedResults', 'query', 'categories'));
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

        // // Apply sorting
        // switch ($sortBy) {
        //     case 'name_asc':
        //         $queryBuilder->orderBy('PackageName', 'asc');
        //         break;
        //     case 'name_desc':
        //         $queryBuilder->orderBy('PackageName', 'desc');
        //         break;
        //     case 'price_asc':
        //         $queryBuilder->orderBy('Price', 'asc');
        //         break;
        //     case 'price_desc':
        //         $queryBuilder->orderBy('Price', 'desc');
        //         break;
        //     case 'username_asc':
        //         $queryBuilder->leftJoin('users', 'packages.UserID', '=', 'users.id')
        //         ->orderBy('users.username');
        //         break;
        //     case 'username_desc':
        //         $queryBuilder->leftJoin('users', 'packages.UserID', '=', 'users.id')
        //         ->orderByDesc('users.username');
        //         break;
        //     // Add more sorting options as needed
        // }

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
            $imagesQuery->whereHas('user', function ($q) use ($authorSearch) {
                $q->where('username', 'like', "%$authorSearch%");
            });
        }
        
        $packages = $queryBuilder->paginate(8);
        $images = $imagesQuery->get();

        // Combine packages and images into a single collection
        $results = $packages->concat($images);

        // Apply sorting
        switch ($sortBy) {
            case 'name_asc':
                $results = $results->sortBy(function ($result) {
                    return $result instanceof Package ? $result->PackageName : $result->ImageName;
                });
                break;
            case 'name_desc':
                $results = $results->sortByDesc(function ($result) {
                    return $result instanceof Package ? $result->PackageName : $result->ImageName;
                });
                break;
            case 'price_asc':
                $results = $results->sortBy('Price');
                break;
            case 'price_desc':
                $results = $results->sortByDesc('Price');
                break;
            case 'username_asc':
                $results = $results->sortBy(function ($result) {
                    return $result->user->username;
                });
                break;
            case 'username_desc':
                $results = $results->sortByDesc(function ($result) {
                    return $result->user->username;
                });
                break;
            // Add more sorting options as needed
        }

        // Paginate the sorted results
        $sortedResults = $this->paginateResults($results, 8);

        // Ensure that paginator uses bootstrap styling
        Paginator::useBootstrap();

        // Pass categories to the view
        return view('search', compact('sortedResults', 'query', 'categories', 'categoryIds', 'priceRanges'));
    }

    private function paginateResults($results, $perPage)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $results->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginator = new LengthAwarePaginator($currentItems, $results->count(), $perPage);
        return $paginator->withPath(LengthAwarePaginator::resolveCurrentPath());
    }

}
