<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\Categories;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $packages = Package::where('PackageName', 'like', "%$query%")->paginate(12);

        // Retrieve categories
        $categories = Categories::all(); // Assuming Category is the model for your categories

        return view('search', compact('packages', 'query', 'categories'));
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
            $queryBuilder->orderBy('user.username', 'asc');
            break;
        case 'username_desc':
            $queryBuilder->orderBy('user.username', 'desc');
            break;
        // Add more sorting options as needed
    }

    $packages = $queryBuilder->paginate(12);

    // Pass categories to the view
    return view('search', compact('packages', 'query', 'categories', 'categoryIds', 'priceRanges'));
}

}
