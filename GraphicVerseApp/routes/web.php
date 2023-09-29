<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RestrictDirectAccess;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AudiosController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TwoDimController;
use App\Http\Controllers\TeamController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\TwoDsController;
use App\Http\Controllers\ThreeDsController2;
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

//ADMIN
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/admin/add-category', [AdminController::class, 'addCategory'])->name('admin.addCategory');
    Route::post('/admin/add-category', [AdminController::class, 'storeCategory'])->name('admin.storeCategory');
    Route::get('/admin/delete-category/{id}', [AdminController::class, 'deleteCategory'])->name('admin.deleteCategory');
    // Other admin routes go here
});




Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');   
Route::get('/', [HomeController::class, 'index'])->name('home');   
Route::get('/aboutus', [AboutUsController::class, 'index'])->name('aboutus');

//User profile
Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');

//2D router controller
Route::get('/2d', [TwoDsController::class, 'index'])->name('twoD.index');
Route::get('2d/{id}', [TwoDsController::class, 'show'])->name('twoD.show');

//3D router controller
Route::get('/3d', [ThreeDsController2::class, 'index'])->name('threeD.index');
Route::get('3d/{id}', [ThreeDsController2::class, 'show'])->name('threeD.show');

//Search Controller
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::middleware(['auth'])->group(function () {
    //profile router contoller
    Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');


    //Teams router
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}', [TeamController::class, 'details'])->name('teams.details');
    Route::delete('/teams/{team}/leave', [TeamController::class, 'leaveTeam'])->name('teams.leave');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');

    Route::get('/teams/{team}/add-members', [TeamController::class, 'addMembers'])->name('teams.addMembers');
    Route::post('/teams/{team}/add-members', [TeamController::class, 'storeMembers'])->name('teams.storeMembers');

    //2D router controller
    Route::get('/upload/2d', [TwoDsController::class, 'create'])->name('twoD.create');
    Route::post('/upload/2d', [TwoDsController::class, 'store'])->name('twoD.store');
    Route::get('/2d/{id}/edit', [TwoDsController::class, 'edit'])->name('twoD.edit');
    Route::put('/2d/{id}', [TwoDsController::class, 'update'])->name('twoD.update');
    Route::delete('/2d/{id}', [TwoDsController::class, 'destroy'])->name('twoD.destroy');

    //3D router controller
    Route::get('/upload/3d', [ThreeDsController2::class, 'create'])->name('threeD.create');
    Route::post('/upload/3d', [ThreeDsController2::class, 'store'])->name('threeD.store');
    Route::get('/3d/{id}/edit', [ThreeDsController2::class, 'edit'])->name('threeD.edit');
    Route::put('/3d/{id}', [ThreeDsController2::class, 'update'])->name('threeD.update');
    Route::delete('/3d/{id}', [ThreeDsController2::class, 'destroy'])->name('threeD.destroy');
});


// //packages router controller
// Route::get('/packages/show', [PackageController::class, 'show'])->name('packages.show');
// Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
// Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');

// // audio controller
// Route::get('/audios/{id}/play',[AudiosController::class, 'play'])->name('audios.play');
// Route::get('/audios/create', [AudiosController::class, 'create'])->name('audios.create');
// Route::post('/audios', [AudiosController::class,'store'])->name('audios.store');

// //two dimensional controller
// Route::get('/two-dim/create', [TwoDimController::class, 'create'])->name('two-dim.create');

// // three-dimensional controller
// Route::get('/three-dim/create', [ThreeDsController::class, 'create'])->name('create');
// Route::get('/three-dim/show/{id}', [ThreeDsController::class, 'show'])->name('show');
// Route::post('/three-dim', [ThreeDsController::class, 'store'])->name('store');

Route::middleware([RestrictDirectAccess::class])->group(function () {

  
    
});