<?php

namespace App\Http\Controllers;

use App\Models\ImageAsset;
use App\Models\Package;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to register route if user is not authenticated
        }
        $user = Auth::user();
        // Get packages purchased by the user
        $packages = Purchase::where('UserID', $user->id)
            ->whereNotNull('package_id')
            ->get();
    
        // Get artworks purchased by the user
        $artworks = Purchase::where('UserID', $user->id)
            ->whereNotNull('artwork_id')
            ->get();
    
        // Load related Package and AssetImage models for packages and artworks
        foreach ($packages as $package) {
            $package->package = Package::find($package->package_id);
        }
    
        foreach ($artworks as $artwork) {
            $artwork->artwork = ImageAsset::find($artwork->artwork_id);
        }
    
        // Pass the purchases with related models to the view
        return view('purchased.index', compact('packages', 'artworks'));
    }

    
    public function show($id)
    {
        
    }


    public function destroy($id)
    {
        //
    }
}
