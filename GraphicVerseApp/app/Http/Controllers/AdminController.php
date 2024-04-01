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
use App\Models\Model2D;
use App\Models\Model3D;
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
        // $models2D = Model2D::all();
        // $models3D = Model3D::all();
        return view('admin.dashboard', ['admin' => $admin, 'categories' => $categories, 'users' => $users, 'packages' => $packages]);
    }

    public function users()
    {
        $admin = Admin::findOrFail(auth()->user()->id); // Fetch the authenticated admin from the database
        $users = User::paginate(5);

        return view('admin.users', ['admin' => $admin, 'users' => $users]);
    }

    public function userDetails($id)
    {
        $admin = Admin::findOrFail(auth()->user()->id);
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }

        // Count the number of 2D uploads for the user
        $userUploadsCount2D = $user->model2D()->count();

        // Count the number of 3D uploads for the user
        $userUploadsCount3D = $user->model3D()->count();
        
        return view('admin.userDetails', [
            'admin' => $admin, 'user' => $user, 
            'userUploadsCount2D' => $userUploadsCount2D,
            'userUploadsCount3D' => $userUploadsCount3D,
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
        $packages = Package::paginate(5);

        return view('admin.packages', ['admin' => $admin, 'packages' => $packages]);
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('admin')->logout();
  
        return redirect('/admin/login');
    }
}
