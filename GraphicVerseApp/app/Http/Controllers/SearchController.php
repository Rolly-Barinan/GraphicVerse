<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Model2D;
use App\Models\Model3D;
use App\Models\Categories;


class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $categories = Categories::all();
        $selectedCategories = $request->input('categories', []);
        
        $models2DQuery = Model2D::query();

        if (!empty($selectedCategories)) {
            $models2DQuery->whereHas('categories2D', function ($query) use ($selectedCategories) {
                $query->whereIn('cat_id', $selectedCategories);
            });
        }

        $models2D = $models2DQuery->get();
        
        $models3DQuery = Model3D::query();

        if (!empty($selectedCategories)) {
            $models3DQuery->whereHas('categories3D', function ($query) use ($selectedCategories) {
                $query->whereIn('cat_id', $selectedCategories);
            });
        }

        $models3D = $models3DQuery->get();

        $models2D = Model2D::where('twoD_name', 'like', '%' . $query . '%')->get();
        $models3D = Model3D::where('threeD_name', 'like', '%' . $query . '%')->get();

        return view('search', compact('models2D', 'models3D', 'categories', 'selectedCategories'));
    }
}
