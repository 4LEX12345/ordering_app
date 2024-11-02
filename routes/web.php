<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\FlavorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderHeaderController;
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

    Route::prefix('/role')->group(function() {
        Route::get('/', [App\Http\Controllers\RoleController::class, 'index']);
        Route::get('fetchdata', [App\Http\Controllers\RoleController::class, 'fetchData']);
        Route::post('store', [App\Http\Controllers\RoleController::class, 'store']);
        Route::put('update/{id}', [App\Http\Controllers\RoleController::class, 'update']);
        Route::get('destroy/{id}', [App\Http\Controllers\RoleController::class, 'destroy']);
    });

    Route::prefix('/paymentmethod')->group(function() {
        Route::get('/', [App\Http\Controllers\PaymentMethodController::class, 'index']);
        Route::get('fetchdata', [App\Http\Controllers\PaymentMethodController::class, 'fetchData']);
        Route::post('store', [App\Http\Controllers\PaymentMethodController::class, 'store']);
        Route::put('update/{id}', [App\Http\Controllers\PaymentMethodController::class, 'update']);
        Route::get('destroy/{id}', [App\Http\Controllers\PaymentMethodController::class, 'destroy']);
    });


    Route::prefix('/brand')->group(function() {
        Route::get('/', [App\Http\Controllers\BrandController::class, 'index']);
        Route::get('fetchdata', [App\Http\Controllers\BrandController::class, 'fetchData']);
        Route::post('store', [App\Http\Controllers\BrandController::class, 'store']);
        Route::put('update/{id}', [App\Http\Controllers\BrandController::class, 'update']);
        Route::get('destroy/{id}', [App\Http\Controllers\BrandController::class, 'destroy']);
    });

    Route::prefix('/flavor')->group(function() {
        Route::get('/', [App\Http\Controllers\FlavorController::class, 'index'])->name('brand');
        Route::get('fetchdata', [App\Http\Controllers\FlavorController::class, 'fetchData']);
        Route::post('store', [App\Http\Controllers\FlavorController::class, 'store']);
        Route::put('update/{id}', [App\Http\Controllers\FlavorController::class, 'update']);
        Route::get('destroy/{id}', [App\Http\Controllers\FlavorController::class, 'destroy']);
    });

    Route::prefix('/productcategory')->group(function() {
        Route::get('/', [App\Http\Controllers\ProductCategoryController::class, 'index']);
        Route::get('fetchdata', [App\Http\Controllers\ProductCategoryController::class, 'fetchData']);
        Route::post('store', [App\Http\Controllers\ProductCategoryController::class, 'store']);
        Route::put('update/{id}', [App\Http\Controllers\ProductCategoryController::class, 'update']);
        Route::get('destroy/{id}', [App\Http\Controllers\ProductCategoryController::class, 'destroy']);
    });

    Route::prefix('/product')->group(function() {
        Route::get('/', [App\Http\Controllers\ProductController::class, 'index']);
        Route::get('fetchdata', [App\Http\Controllers\ProductController::class, 'fetchData']);
        Route::post('store', [App\Http\Controllers\ProductController::class, 'store']);
        Route::post('update', [App\Http\Controllers\ProductController::class, 'update']);
        Route::get('destroy/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);
    });

    Route::prefix('/order')->group(function() {
        Route::get('/', [App\Http\Controllers\OrderHeaderController::class, 'index']);
        Route::get('fetchdata', [App\Http\Controllers\OrderHeaderController::class, 'fetchData']);
        Route::post('store', [App\Http\Controllers\OrderHeaderController::class, 'store']);
        Route::get('/edit/{id}', [App\Http\Controllers\OrderHeaderController::class, 'edit']);
        Route::post('update', [App\Http\Controllers\OrderHeaderController::class, 'update']);
        Route::get('destroy/{id}', [App\Http\Controllers\OrderHeaderController::class, 'destroy']);
        Route::get('generateinvoice/{id}', [App\Http\Controllers\OrderHeaderController::class, 'generateInvoice']);
        Route::get('generatefinalinvoice/{id}', [App\Http\Controllers\OrderHeaderController::class, 'generateFinalInvoice']);
        Route::get('showinvoice', [App\Http\Controllers\OrderHeaderController::class, 'showInvoice']);
        Route::get('showfinalinvoice', [App\Http\Controllers\OrderHeaderController::class, 'showFinalInvoice']);
        Route::get('downloadproofofpayment/{path}', [App\Http\Controllers\OrderHeaderController::class, 'downloadProofOfPayment'])->where('path', '.*');
    });

    Route::prefix('/systemparameter')->group(function() {
        Route::get('/', [App\Http\Controllers\SystemParameterController::class, 'index']);
        Route::get('fetchdata', [App\Http\Controllers\SystemParameterController::class, 'fetchData']);
        Route::post('store', [App\Http\Controllers\SystemParameterController::class, 'store']);
        Route::put('update/{id}', [App\Http\Controllers\SystemParameterController::class, 'update']);
        Route::get('destroy/{id}', [App\Http\Controllers\SystemParameterController::class, 'destroy']);
    });

   
  
    
    
});
