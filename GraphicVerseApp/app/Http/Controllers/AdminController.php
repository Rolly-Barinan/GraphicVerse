<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Admin;
use App\Models\Categories;
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
        $categories = Categories::paginate(5);

        return view('admin.dashboard', ['admin' => $admin, 'categories' => $categories]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|max:255', // Add any validation rules you need
        ]);
    
        // Create a new category instance and set its properties
        $category = new Categories();
        $category->cat_name = $request->input('category');
    
        // Save the category to the database
        $category->save();
    
        return redirect()->route('admin.dashboard')->with('success', 'Category added successfully.');
    }

    public function deleteCategory($id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return redirect()->route('admin.dashboard')->with('error', 'Category not found.');
        }

        // Perform any additional checks or logic before deleting if needed

        $category->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Category deleted successfully.');
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('admin')->logout();
  
        return redirect('/admin/login');
    }
}
