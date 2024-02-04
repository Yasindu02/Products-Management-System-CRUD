<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('products', [ProductController::class,'index']);
Route::post('store', [ProductController::class,'store']);
Route::post('edit', [ProductController::class,'edit']);
Route::post('delete', [ProductController::class,'destroy']);
