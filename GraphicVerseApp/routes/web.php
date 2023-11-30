
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RestrictDirectAccess;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TwoDsController;
use App\Http\Controllers\ThreeDsController2;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AssetPackageController;
use App\Http\Controllers\PaypalController;

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
Route::get('/asset-package', [AssetPackageController::class, 'index'])->name('asset.index');
Route::get('/asset-package/create', [AssetPackageController::class, 'create'])->name('asset.create');
Route::get('/asset-package/{id}', [AssetPackageController::class, 'show'])->name('asset.show');
Route::get('/asset-package3d/{id}', [AssetPackageController::class, 'display3d'])->name('asset.display3d');
Route::get('/asset-package2d/{id}', [AssetPackageController::class, 'display2d'])->name('asset.display2d');

Route::post('/asset-package/store', [AssetPackageController::class, 'store'])->name('asset.store');
Route::get('/asset-package/{id}/download', [AssetPackageController::class, 'download'])->name('asset.download');

////////paypal 
Route::post('paypal/payment', [PaypalController::class, 'payment'])->name('paypal');
Route::get('paypal/success', [PaypalController::class, 'success'])->name('paypal_success');
Route::get('paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal_cancel');

//User profile
Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');

//2D router controller
Route::get('/2d', [TwoDsController::class, 'index'])->name('twoD.index');
Route::get('2d/{id}', [TwoDsController::class, 'show'])->name('twoD.show');
Route::get('/twoD/download/{id}',  [TwoDsController::class, 'download'])->name('twoD.download');


//3D router controller
Route::get('/3d', [ThreeDsController2::class, 'index'])->name('threeD.index');
Route::get('/three-dim/{id}/download', [ThreeDsController2::class, 'download'])->name('threeD.download');
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

Route::middleware([RestrictDirectAccess::class])->group(function () {
});
