<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Front\DetailController;
use App\Http\Controllers\Front\CatalogController;
use App\Http\Controllers\Front\LandingController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Front\CheckOutController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Admin\TypeController as AdminTypeController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

Route::name('front.')->group(function(){
    Route::get('/',[LandingController::class,'index'])->name('index');
    Route::get('detail/{slug}',[DetailController::class,'index'])->name('detail');
    Route::get('/catalog',[CatalogController::class,'index'])->name('catalog');

    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/checkout/{slug}', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout/{slug}', [CheckoutController::class, 'store'])->name('checkout.store');
    
    
    Route::get('/payment/{bookingId}', [PaymentController::class, 'index'])->name('payment');
    Route::post('/payment/{bookingId}', [PaymentController::class, 'update'])->name('payment.update');
   
    });
});

Route::prefix( 'admin')->name( 'admin-')->middleware([
    'auth:sanctum', 
    config('jetstream.auth_session'),
    'verified',
    
    
])->group(function () {
    Route::get('/dashboard',[AdminDashboard::class,'index'])->name('dashboard');
    Route::resource('brands',AdminBrandController::class);
    Route::resource('types', AdminTypeController::class);
    Route::resource('items', AdminItemController::class);
    Route::resource('bookings', AdminBookingController::class);
});

 