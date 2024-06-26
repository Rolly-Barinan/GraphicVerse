<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use App\Models\Admin;
use App\Models\Categories;
use App\Models\User;
use App\Models\Package;
use App\Models\ImageAsset;
use App\Models\Asset;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')->with('error', 'Invalid credentials.');
    }

    public function dashboard()
    {
        $admin = Admin::findOrFail(auth()->user()->id); // Fetch the authenticated admin from the database
        $categories = Categories::all();
        $users = User::all();
        $packages = Package::all();
        $assets = Asset::all();
        $images = ImageAsset::all();
        // $models2D = Model2D::all();
        // $models3D = Model3D::all();
        return view('admin.dashboard', ['admin' => $admin, 'categories' => $categories, 'users' => $users, 'packages' => $packages, 'assets' => $assets, 'images' => $images]);
    }

    public function users()
    {
        $admin = Admin::findOrFail(auth()->user()->id); // Fetch the authenticated admin from the database
        $users = User::paginate(10);

        return view('admin.users', ['admin' => $admin, 'users' => $users]);
    }

    public function userSearch(Request $request)
    {
        $searchQuery = $request->input('q');

        $users = User::where('username', 'like', '%'.$searchQuery.'%')->paginate(10);

        return view('admin.userSearchResults', ['users' => $users])->render();
    }

    public function userDetails($id)
    {
        $admin = Admin::findOrFail(auth()->user()->id);
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }

        // Count the number of Packages uploads for the user
        $userUploadsCountPackages = $user->packages()->count();

        // Count the number of Assets uploads for the user
        $userUploadsCountAssets = $user->assets()->count();
        
        $userUploadsCountArtworks = $user->images()->count();

        return view('admin.userDetails', [
            'admin' => $admin, 'user' => $user, 
            'userUploadsCountPackages' => $userUploadsCountPackages,
            'userUploadsCountAssets' => $userUploadsCountAssets,
            'userUploadsCountArtworks' => $userUploadsCountArtworks,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }

        // Perform any additional checks or logic before deleting if needed

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function categories()
    {
        $admin = Admin::findOrFail(auth()->user()->id); // Fetch the authenticated admin from the database
        $categories = Categories::paginate(10);

        return view('admin.categories', ['admin' => $admin, 'categories' => $categories]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'category' => ['required', 'string', 'max:255', Rule::unique('categories', 'cat_name')], // Add any validation rules you need
        ]);
    
        // Create a new category instance and set its properties
        $category = new Categories();
        $category->cat_name = $request->input('category');
    
        // Save the category to the database
        $category->save();
    
        return redirect()->route('admin.categories')->with('success', 'Category added successfully.');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'category' => ['required', 'string', 'max:255', Rule::unique('categories', 'cat_name')->ignore($id)], // Add any validation rules you need
        ]);

        $category = Categories::find($id);

        if (!$category) {
            return redirect()->route('admin.categories')->with('error', 'Category not found.');
        }

        $category->cat_name = $request->input('category');
        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }

    public function deleteCategory($id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return redirect()->route('admin.dashboard')->with('error', 'Category not found.');
        }

        // Perform any additional checks or logic before deleting if needed

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully.');
    }

    public function packages()
    {
        $admin = Admin::findOrFail(auth()->user()->id); // Fetch the authenticated admin from the database
        $packages = Package::paginate(10);

        return view('admin.packages', ['admin' => $admin, 'packages' => $packages]);
    }

    public function packageSearch(Request $request)
    {
        $searchQuery = $request->input('q');

        $packages = Package::where('PackageName', 'like', '%'.$searchQuery.'%')->paginate(10);

        return view('admin.packageSearchResults', ['packages' => $packages])->render();
    }

    public function packageDetails($id)
    {
        $admin = Admin::findOrFail(auth()->user()->id);
        $user = User::find($id);
        $package = Package::find($id);

        if (!$package) {
            return redirect()->route('admin.packages')->with('error', 'Package not found.');
        }

        // Count the number of Assets uploads for the user
        $userUploadsCountAssets = $package->assets()->count();
        
        return view('admin.packageDetails', [
            'admin' => $admin, 'user' => $user, 'package' => $package, 
            'userUploadsCountAssets' => $userUploadsCountAssets,
        ]);
    }

    public function deletePackage($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return redirect()->route('admin.packages')->with('error', 'Package not found.');
        }

        // Perform any additional checks or logic before deleting if needed

        $package->delete();

        return redirect()->route('admin.packages')->with('success', 'Package deleted successfully.');
    }

    public function images()
    {
        $admin = Admin::findOrFail(auth()->user()->id); // Fetch the authenticated admin from the database
        $images = ImageAsset::paginate(10);

        return view('admin.imageAssets', ['admin' => $admin, 'images' => $images]);
    }

    public function imageSearch(Request $request)
    {
        $searchQuery = $request->input('q');

        $images = ImageAsset::where('ImageName', 'like', '%'.$searchQuery.'%')->paginate(10);

        return view('admin.imageSearchResults', ['images' => $images])->render();
    }

    public function imageDetails($id)
    {
        $admin = Admin::findOrFail(auth()->user()->id);
        $user = User::find($id);
        $image = ImageAsset::find($id);

        if (!$image) {
            return redirect()->route('admin.imageAssets')->with('error', 'Artwork not found.');
        }
        
        return view('admin.imageDetails', [
            'admin' => $admin, 'user' => $user, 'image' => $image
        ]);
    }

    public function deleteImage($id)
    {
        $image = ImageAsset::find($id);

        if (!$image) {
            return redirect()->route('admin.imageAssets')->with('error', 'Image not found.');
        }

        // Perform any additional checks or logic before deleting if needed

        $image->delete();

        return redirect()->route('admin.imageAssets')->with('success', 'Image deleted successfully.');
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('admin')->logout();
  
        return redirect('/admin/login');
    }
}
