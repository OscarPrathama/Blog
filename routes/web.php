<?php
use Illuminate\Support\Facades\Route;
require __DIR__.'/auth.php';

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

Route::get('/admin/dashboard', 'Admin\DashboardController@index')->middleware(['auth'])->name('dashboard');
Route::get('/admin/my-profile', 'Admin\UserController@myProfile')->middleware(['auth'])->name('my-profile');

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
        Route::post('bulk-action', 'Admin\PostController@bulkAction')->name('posts-bulk-action');
    });

    Route::prefix('admin/media')->group(function(){
        Route::get('/', 'Admin\MediaController@index')->name('media');
        Route::post('/update_image', 'Admin\MediaController@updateImg')->name('media-update');
        Route::post('/delete_image', 'Admin\MediaController@deleteImg')->name('media-delete');
    });
});

Route::get('/', 'FrontpageController@index')->name('frontpage');
Route::get('/blogs', 'BlogController@index')->name('blogs');
Route::get('/about-us', 'AboutUsController@index')->name('about-us');
Route::get('/contact', 'ContactController@index')->name('contact');

// Route::get('/admin/dashboard', function () {
//     return view('__dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::get('{slug}', 'FrontpageController@show')->name('posts-show');
