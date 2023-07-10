<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\ThreeDsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');   

Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');
Route::get('/aboutus', [AboutUsController::class, 'index'])->name('aboutus');
Route::get('/p/create', [ThreeDsController::class, 'create'])->name('create');
Route::post('/p', [ThreeDsController::class, 'store'])->name('store');
Route::get('profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update'); 