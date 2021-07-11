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
Route::get('/admin', function () {
    return redirect('/admin/dashboard');
});

Route::middleware('auth')->group(function(){

    // frontpage
    Route::prefix('admin/pages/frontpage')->group(function(){
        Route::get('edit/{id}', 'Admin\Pages\FrontpageController@edit')->name('frontpage-edit');
        Route::patch('update/{id}', 'Admin\Pages\FrontpageController@update')->name('frontpage-update');
    });

    // pages
    Route::prefix('admin/pages')->group(function(){
        Route::get('/', 'Admin\PageController@index')->name('pages-index');
        Route::get('show', 'Admin\PageController@store')->name('pages-show');
        Route::get('create', 'Admin\PageController@create')->name('pages-create');
        Route::get('bulk-action', 'Admin\PageController@store')->name('pages-bulk-action');
        Route::get('search', 'Admin\PageController@store')->name('pages-search');
        Route::get('{slug}/edit/{id}', 'Admin\PageController@edit')->name('pages-edit');
        Route::post('edit/remove_img/', 'Admin\PageController@editRemoveImage')->name('pages-edit-remove-image');
        Route::post('update', 'Admin\PageController@store')->name('pages-update');
        Route::post('quick-update', 'Admin\PageController@quickUpdate')->name('pages-quick-edit');
        Route::get('export-excel', 'Admin\PageController@exportExcel')->name('pages-export-excel');
        /*
        Route::get('store', 'Admin\PageController@store')->name('pages-store');
        Route::get('delete', 'Admin\PageController@store')->name('pages-delete');
        */
    });

    // posts
    Route::prefix('admin/posts')->group(function () {
        Route::get('/', 'Admin\PostController@index')->name('posts-index');
        Route::get('search', 'Admin\PostController@search')->name('posts-search');
        Route::get('create', 'Admin\PostController@create')->name('posts-create');
        Route::post('store', 'Admin\PostController@store')->name('posts-store');
        Route::post('quick_update', 'Admin\PostController@quickUpdate')->name('posts-quick-edit');
        Route::get('edit/{id}', 'Admin\PostController@edit')->name('posts-edit');
        Route::post('edit/remove_img/', 'Admin\PostController@editRemoveImage')->name('posts-edit-remove-image');
        Route::post('update/{id}', 'Admin\PostController@update')->name('posts-update');
        Route::get('delete/{id}', 'Admin\PostController@delete')->name('posts-delete');
        Route::post('bulk-action', 'Admin\PostController@bulkAction')->name('posts-bulk-action');
        Route::get('export-excel', 'Admin\PostController@exportExcel')->name('posts-export-excel');
    });

    // media
    Route::prefix('admin/media')->group(function(){
        Route::get('/', 'Admin\MediaController@index')->name('media');
        Route::post('/update_image', 'Admin\MediaController@updateImg')->name('media-update');
        Route::post('/delete_image', 'Admin\MediaController@deleteImg')->name('media-delete');
    });

    // search
    Route::prefix('admin/')->group(function(){
        // put on the top from resource route, so it can be found
        Route::get('inbox/search/', 'Admin\InboxController@searchInbox')->name('inbox.search');
        Route::post('inbox/ajax_delete', 'Admin\InboxController@deleteInbox')->name('ajax.inbox.delete');
        Route::post('inbox/bulk_action', 'Admin\InboxController@bulkAction')->name('inbox.bulk.action');

        Route::resource('inbox', 'Admin\InboxController');
    });

    // setting
    Route::prefix('admin/settings')->group(function(){
        Route::get('/general', 'Admin\SettingController@index')->name('settings.index');
        Route::post('/general/update_or_create', 'Admin\SettingController@_updateOrCreate')->name('settings.updateOrCreate');
    });

    // menu
    Route::prefix('admin/menu')->group(function(){
        Route::get('', 'Admin\MenuController@index')->name('menus.index');
        Route::post('/update_or_create', 'Admin\MenuController@_updateOrCreate')->name('menus.store');
    });

    // users
    Route::prefix('admin')->group(function(){
        Route::resource('users', 'Admin\UserController');
        Route::get('/users/search', 'Admin\UserController@search')->name('users.search');
        Route::post('/users/remove_user_image', 'Admin\UserController@removeImage')->name('user.edit.remove.image');
    });

    // roles
    Route::prefix('admin')->group(function(){
        Route::resource('roles', 'Admin\RoleController')->except(['show']);
        Route::get('roles/search', 'Admin\RoleController@search')->name('roles.search');
    });

    // permissions
    Route::prefix('admin')->group(function(){
        Route::get('permissions', 'Admin\PermissionController@index')->name(('permissions.index'));
        Route::get('search', 'Admin\PermissionController@Search')->name('permissions.search');
    });

});

Route::get('/', 'FrontpageController@index')->name('frontpage');
Route::post('/', 'FrontpageController@storeInbox')->name('fp-store-inbox');
Route::get('/blogs', 'BlogController@index')->name('blogs');
Route::get('/about-us', 'AboutUsController@index')->name('about-us');
Route::get('/contact-us', 'ContactController@index')->name('contact-us');
Route::get('/posts-api', 'Admin\PostController@postApi')->name('posts-api');

// redirect
Route::get('/frontpage', function () {
    return redirect('/');
});



// Route::get('/admin/dashboard', function () {
//     return view('__dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::get('{slug}', 'FrontpageController@show')->name('posts-show');
