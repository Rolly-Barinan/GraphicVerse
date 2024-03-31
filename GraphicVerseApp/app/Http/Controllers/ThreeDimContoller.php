<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;

class ThreeDimContoller extends Controller
{

    public function index()
    {
        $categories = Categories::all();
        $packages = Package::all();
        return view('threeDim.index', compact('packages', 'categories'));
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

        return view('threeDim.show', compact('package', 'assets', 'totalSizeMB', 'fileTypes', 'user'));
    }

    public function filterPackages(Request $request)
    {
        $categoryIds = $request->input('categories');

        if (!is_array($categoryIds) || empty($categoryIds)) {
            $packages = Package::all();
        } else {
            $packages = Package::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })->get();
        }
        $categories = Categories::all();
        return view('threeDim.index', compact('packages', 'categories'));
    }
}