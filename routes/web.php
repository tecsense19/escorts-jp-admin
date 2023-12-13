<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\EscortsController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SettingsController;

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

    Route::get('/escorts', [EscortsController::class, 'showEscorts'])->name('admin.show.escorts');
    Route::get('/escorts/create', [EscortsController::class, 'create'])->name('admin.create.escorts');
    Route::get('/escorts/edit/{user_id}', [EscortsController::class, 'edit'])->name('admin.edit.escorts');
    Route::post('/escorts/save', [EscortsController::class, 'saveEscorts'])->name('admin.save.escorts');
    Route::post('/escorts/list', [EscortsController::class, 'listEscorts'])->name('admin.escorts.list');
    Route::post('/escorts/delete', [EscortsController::class, 'deleteEscorts'])->name('admin.escorts.delete');
    Route::post('/escorts/change/status', [EscortsController::class, 'changeStatus'])->name('admin.escorts.change.status');
    Route::get('/escorts/view/{user_id}', [EscortsController::class, 'viewProfile'])->name('admin.view.escorts');
    Route::post('/escorts/wise/bookings/list', [EscortsController::class, 'bookingList'])->name('admin.escort.wise.booking.list');

    Route::get('/calendar/event/{user_id}', [EscortsController::class, 'calendarEvent'])->name('admin.calendar.event');

    Route::post('/escorts/availability/add', [EscortsController::class, 'availabilityAdd'])->name('admin.escorts.availability.add');
    Route::post('/escorts/availability/list', [EscortsController::class, 'availabilityList'])->name('admin.escorts.availability.list');
    
    Route::post('/state/list', [HomeController::class, 'stateList'])->name('admin.state.list');
    Route::post('/city/list', [HomeController::class, 'cityList'])->name('admin.city.list');

    Route::get('/bookings', [BookingController::class, 'index'])->name('admin.booking');
    Route::post('/bookings/list', [BookingController::class, 'bookingList'])->name('admin.booking.list');
    Route::post('/bookings/delete', [BookingController::class, 'bookingDelete'])->name('admin.booking.delete');

    Route::get('/clients', [ClientsController::class, 'index'])->name('admin.clients');
    Route::post('/clients/list', [ClientsController::class, 'listClients'])->name('admin.clients.list');
    Route::post('/clients/delete', [ClientsController::class, 'deleteClient'])->name('admin.clients.delete');

    Route::get('/language', [LanguageController::class, 'index'])->name('admin.language');
    Route::post('/language/list', [LanguageController::class, 'languageList'])->name('admin.language.list');
    Route::post('/language/delete', [LanguageController::class, 'languageDelete'])->name('admin.language.delete');
    Route::post('/language/save', [LanguageController::class, 'saveString'])->name('admin.save.string');


    Route::get('/settings/privacy', [SettingsController::class, 'index'])->name('admin.settings.privacypolicy');
    Route::Post('/settings/privacy/save', [SettingsController::class, 'saveprivacy'])->name('admin.save.privacypolicy');

    Route::get('/settings/terms', [SettingsController::class, 'termindex'])->name('admin.settings.termcondition');
    Route::Post('/settings/terms/save', [SettingsController::class, 'saveterm'])->name('admin.save.termcondition');

});

Route::get('/privacypolicy',[SettingsController::class, 'privacypolicy'])->name('privacypolicy');
Route::get('/termscondition',[SettingsController::class, 'termscondition'])->name('termscondition');

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');

    return "Cache cleared successfully";
});