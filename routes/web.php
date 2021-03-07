<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/admin/dashboard', 'Admin\DashboardController@index')->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function(){
    Route::prefix('admin/posts')->group(function () {
        Route::get('/', 'Admin\PostController@index')->name('posts-index');
        Route::get('search', 'Admin\PostController@search')->name('posts-search');
        Route::get('create', 'Admin\PostController@create')->name('posts-create');
        Route::post('store', 'Admin\PostController@store')->name('posts-store');
        Route::post('quick_update', 'Admin\PostController@quickUpdate')->name('posts-quick-edit');
        Route::get('edit/{id}', 'Admin\PostController@edit')->name('posts-edit');
        Route::post('update/{id}', 'Admin\PostController@update')->name('posts-update');
        Route::get('delete/{id}', 'Admin\PostController@delete')->name('posts-delete');
        // Route::get('{slug}', 'Admin\PostController@show')->name('posts-show');
        Route::post('bulk-action', 'Admin\PostController@bulkAction')->name('posts-bulk-action');
    });
});

Route::get('/', 'FrontpageController@index')->name('frontpage');

// Route::get('/admin/dashboard', function () {
//     return view('__dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('{slug}', 'FrontpageController@show')->name('posts-show');