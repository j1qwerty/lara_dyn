<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Roles;

// Route::get('/', function () {
//     return view('dashboard');
// });

Route::get('/', function(){
    return view('welcome');
});

// Route::get('/dashboard', function(){
//     return view('dashboard');
// })->middleware(['auth','verified', Roles::class.':admin,user' ]) ->name('dashboard');

Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::resource('products', ProductsController::class);

// add admin + user routes here
Route::middleware(['auth','verified', Roles::class. ':admin,user'])->group(function (){
    Route::get('/dashboard', function(){ return view('dashboard'); })->name('dashboard');

});

//admin only routes
Route::middleware(['auth','verified', Roles::class. ':admin'])->group(function (){
    Route::get('/admin', function(){ return view('dashboard'); });

});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
