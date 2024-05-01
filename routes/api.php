<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('/books', 'BooksController@store');
// Route::post('/books/{id}/reviews', 'BooksReviewController@store');
// Route::delete('/books/{bookId}/reviews/{reviewId}', 'BooksReviewController@destroy');

Route::middleware('auth:api')->group(function () {
    Route::middleware('auth.admin')->group(function () {
        Route::post('/books', 'BooksController@store');
    });
    Route::post('/books/{id}/reviews', 'BooksReviewController@store');
    Route::delete('/books/{bookId}/reviews/{reviewId}', 'BooksReviewController@destroy');
});
Route::get('/books', 'BooksController@index');
