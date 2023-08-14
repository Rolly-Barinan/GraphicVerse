<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Admin;
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
        return view('admin.dashboard', ['admin' => $admin]);
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('admin')->logout();
  
        return redirect('/admin/login');
    }
}
