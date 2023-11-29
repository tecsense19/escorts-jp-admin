<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\EscortsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/admin', [AdminAuthController::class, 'index'])->name('admin.login');
Route::get('/', [AdminAuthController::class, 'index'])->name('admin.login');
Route::post('/admin/custom/login', [AdminAuthController::class, 'customLogin'])->name('login.custom');
Route::get('/admin/signout', [AdminAuthController::class, 'signOut'])->name('signout');

// Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
Route::group(['middleware' => ['admin']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [HomeController::class, 'profile'])->name('admin.profile');
    Route::post('/profile/update', [HomeController::class, 'profileUpdate'])->name('admin.profile.update');
    Route::post('/update/password', [HomeController::class, 'updatePassword'])->name('admin.update.password');

    Route::get('/escorts/create', [EscortsController::class, 'create'])->name('admin.create.escorts');
    Route::post('/escorts/save', [EscortsController::class, 'saveEscorts'])->name('admin.save.escorts');
    Route::get('/escorts/show', [EscortsController::class, 'showEscorts'])->name('admin.show.escorts');
    Route::post('/escorts/list', [EscortsController::class, 'listEscorts'])->name('admin.escorts.list');
    Route::post('/escorts/delete', [EscortsController::class, 'deleteEscorts'])->name('admin.escorts.delete');
    
    Route::post('/state/list', [HomeController::class, 'stateList'])->name('admin.state.list');
    Route::post('/city/list', [HomeController::class, 'cityList'])->name('admin.city.list');
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');

    return "Cache cleared successfully";
});