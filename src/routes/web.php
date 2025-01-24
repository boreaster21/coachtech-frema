<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;


Route::get('/', [ProductController::class, 'index'])->name('product.index');
Route::get('item/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');


Route::middleware('auth')->group(function () {

    Route::prefix('item')->group(function () {
        Route::post('/{id}/favorite', [ProductController::class, 'favorite'])->name('item.favorite');
        Route::get('/myFavorites', [ProductController::class, 'myFavorites'])->name('item.myFavorites');
        Route::get('/{product}/comments', [ProductController::class, 'showComments'])->name('item.comments');
        Route::post('/{product}/comments', [ProductController::class, 'postComment'])->name('item.comment.store');
    });

    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage'); 
    Route::get('/mypage/listed-items', [UserController::class, 'getListedItems'])->name('mypag.listed');
    Route::get('/mypage/purchased-items', [UserController::class, 'getPurchasedItems'])->name('mypage.purchased');

    Route::prefix('sell')->group(function () {
        Route::get('/', [ProductController::class, 'create'])->name('product.create');
        Route::post('/', [ProductController::class, 'store'])->name('product.store');
    });

    Route::prefix('purchase')->group(function () {
        Route::get('/{product}', [PaymentController::class, 'purchase'])->name('purchase');
        Route::post('/stripe/{product}', [PaymentController::class, 'stripePurchase'])->name('purchase.stripe');
        Route::post('/success/{product}', [PaymentController::class, 'success'])->name('purchase.success');
        Route::get('/success/{product}', [PaymentController::class, 'success'])->name('purchase.success.get');
    });

    Route::get('/payment/edit', [PaymentController::class, 'edit'])->name('payment.edit');
    Route::post('/payment/update', [PaymentController::class, 'update'])->name('payment.update');

    Route::get('/address/edit', [AddressController::class, 'edit'])->name('address.edit');
    Route::post('/address/update', [AddressController::class, 'update'])->name('address.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::delete('/admin/comments/{id}', [AdminController::class, 'deleteComment'])->name('admin.deleteComment');
    Route::post('/admin/send-mail', [AdminController::class, 'sendMail'])->name('admin.sendMail');
});


require __DIR__.'/auth.php';
