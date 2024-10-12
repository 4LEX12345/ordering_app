<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;


// Route for the login page
Route::get('/', function () {
    return view('auth.login'); // Assuming the login view is in resources/views/auth/login.blade.php
})->name('login');

Auth::routes();
Route::group(['middleware' => 'auth'], function(){

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/order', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');


    Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user');
    Route::get('/user/getdata', [App\Http\Controllers\UserController::class, 'getData'])->name('getData');
    Route::post('/user/store', [App\Http\Controllers\UserController::class, 'store'])->name('store');
    Route::post('/user/update', [App\Http\Controllers\UserController::class, 'store'])->name('store');
    Route::get('/user/destroy/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    Route::post('/user/changepassword', [App\Http\Controllers\UserController::class, 'changePassword'])->name('changePassword');
    
});
