
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
use App\Http\Controllers\AudioController;
use App\Http\Controllers\ImageAssetController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\ThreeDimContoller;
use App\Http\Controllers\TwoDimContoller;


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
    Route::get('/admin/packages', [AdminController::class, 'packages'])->name('admin.packages');
    Route::get('/admin/images', [AdminController::class, 'images'])->name('admin.imageAssets');
    Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/admin/add-category', [AdminController::class, 'addCategory'])->name('admin.addCategory');
    Route::post('/admin/add-category', [AdminController::class, 'storeCategory'])->name('admin.storeCategory');
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'editCategory'])->name('admin.editCategory');
    Route::post('/admin/categories/update/{id}', [AdminController::class, 'updateCategory'])->name('admin.updateCategory');
    Route::get('/admin/delete-category/{id}', [AdminController::class, 'deleteCategory'])->name('admin.deleteCategory');

    Route::get('/admin/user/search', [AdminController::class, 'userSearch'])->name('admin.userSearch');
    Route::get('/admin/users/details/{id}', [AdminController::class, 'userDetails'])->name('admin.userDetails');
    Route::get('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');

    Route::get('/admin/packages/search', [AdminController::class, 'packageSearch'])->name('admin.packageSearch');
    Route::get('/admin/packages/details/{id}', [AdminController::class, 'packageDetails'])->name('admin.packageDetails');
    Route::get('/admin/delete-package/{id}', [AdminController::class, 'deletePackage'])->name('admin.deletePackage');

    Route::get('/admin/image/search', [AdminController::class, 'imageSearch'])->name('admin.imageSearch');
    Route::get('/admin/images/details/{id}', [AdminController::class, 'imageDetails'])->name('admin.imageDetails');
    Route::get('/admin/delete-image/{id}', [AdminController::class, 'deleteImage'])->name('admin.deleteImage');
    // Other admin routes go here
});

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/aboutus', [AboutUsController::class, 'index'])->name('aboutus');

//packages
Route::get('/package/{id}/edit', [AssetPackageController::class, 'edit'])->name('asset.edit');
Route::patch('/package/{id}', [AssetPackageController::class, 'update'])->name('asset.update');
Route::get('/package', [AssetPackageController::class, 'index'])->name('asset.index');
Route::get('/package/create', [AssetPackageController::class, 'create'])->name('asset.create');
Route::get('/package/{id}', [AssetPackageController::class, 'show'])->name('asset.show');
Route::post('/package/store', [AssetPackageController::class, 'store'])->name('asset.store');
Route::get('/package/{id}/download', [AssetPackageController::class, 'download'])->name('asset.download');
Route::delete('/package/{package}', [AssetPackageController::class, 'destroy'])->name('asset.destroy');


////Image Asset routes
Route::get('/image', [ImageAssetController::class, 'index'])->name('image.index');
Route::get('/image/create', [ImageAssetController::class, 'create'])->name('image.create');
Route::get('/image/{id}', [ImageAssetController::class, 'show'])->name('image.show');
Route::post('/image/store', [ImageAssetController::class, 'store'])->name('image.store');
Route::get('/image/{id}/download', [ImageAssetController::class, 'download'])->name('image.download');
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

////audios 
Route::get('/audio-models', [AudioController::class, 'index'])->name('audio.index');
Route::get('/audio-models/{id}', [AudioController::class, 'show'])->name('audio.show');
//User profile

//Search Controller
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/{user}', [ProfilesController::class, 'index'])->name('profile.show');

    Route::get('/asset/create', [AssetPackageController::class, 'create'])->name('asset.create');
    Route::get('/image/create', [ImageAssetController::class, 'create'])->name('image.create');
    //profile router contoller
    Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');
    Route::get('/profile/display/2d-models', [ProfilesController::class, 'twoDimDisplay'])->name('profile.twoDimDisplay');
    Route::get('/profile/display/3d-models', [ProfilesController::class, 'threeDimDisplay'])->name('profile.threeDimDisplay');
    Route::get('/profile/display/audio-models', [ProfilesController::class, 'audioDisplay'])->name('profile.audioDisplayaudioDisplay');
    Route::get('/profile/display/image-models', [ProfilesController::class, 'imageDisplay'])->name('profile.imageDisplay');
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
