<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AudiosController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\ThreeDsController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Auth;

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
Route::get('/aboutus', [AboutUsController::class, 'index'])->name('aboutus');
//profile router contoller
Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');
Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');
//packages router controller
Route::get('/packages/show', [PackageController::class, 'show'])->name('packages.show');
Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');

// audio controller
Route::get('/audios/{id}/play',[AudiosController::class, 'play'])->name('audios.play');
Route::get('/audios/create', [AudiosController::class, 'create'])->name('audios.create');
Route::post('/audios', [AudiosController::class,'store'])->name('audios.store');



Route::get('/upload/create', [ThreeDsController::class, 'create'])->name('create');

Route::post('/p', [ThreeDsController::class, 'store'])->name('store');
Route::get('profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update'); 