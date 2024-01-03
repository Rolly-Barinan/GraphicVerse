<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RestrictDirectAccess;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SearchController;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AssetPackageController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\ThreeDimContoller;
use App\Http\Controllers\TwoDimContoller;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

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

//packages
Route::get('/asset', [AssetPackageController::class, 'index'])->name('asset.index');
Route::get('/asset/create', [AssetPackageController::class, 'create'])->name('asset.create');
Route::get('/asset/{id}', [AssetPackageController::class, 'show'])->name('asset.show');
Route::post('/asset/store', [AssetPackageController::class, 'store'])->name('asset.store');
Route::get('/asset/{id}/download', [AssetPackageController::class, 'download'])->name('asset.download');

////////paypal 
Route::post('paypal/payment', [PaypalController::class, 'payment'])->name('paypal');
Route::get('paypal/success', [PaypalController::class, 'success'])->name('paypal_success');
Route::get('paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal_cancel');

//asset layout 2d
Route::get('/2d-models', [TwoDimContoller::class, 'index'])->name('twoDim.index');
Route::get('/2d-models', [TwoDimContoller::class, 'filterPackages'])->name('filter.2d');
Route::get('/2d-models/{id}', [TwoDimContoller::class, 'show'])->name('twoDim.show');

//asset layout 3d
Route::get('/3d-models', [ThreeDimContoller::class, 'index'])->name('threeDim.index');
Route::get('/3d-models', [ThreeDimContoller::class, 'filterPackages'])->name('filter.3d');
Route::get('/3d-models/{id}', [ThreeDimContoller::class, 'show'])->name('threeDim.show');
//User profile
Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');
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

});

Route::middleware([RestrictDirectAccess::class])->group(function () {
});
