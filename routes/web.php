<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});

/*
 * Register the route for a resource controller
 * We don't need routes for all actions. We can use the 'only' method to specify which routes to enable. All others will be disabled.
 */
Route::resource('books', BookController::class)
    ->only(['index', 'show']);

// We scope the routes so that reviews are associated with a specific book
Route::resource('books.reviews', ReviewController::class)
    ->scoped(['review' => 'book'])
    ->only(['create', 'store']);
