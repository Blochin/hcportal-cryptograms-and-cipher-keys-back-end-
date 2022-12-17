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

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['web'])->group(static function () {
    Route::namespace('App\Http\Controllers\Admin')->group(static function () {
        Route::get('/admin/login', 'LoginController@showLoginForm')->name('brackets/admin-auth::admin/login');
        Route::post('/admin/login', 'LoginController@login');

        Route::any('/admin/logout', 'LoginController@logout')->name('brackets/admin-auth::admin/logout');
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::get('/profile',                                      'ProfileController@editProfile')->name('edit-profile');
        Route::post('/profile',                                     'ProfileController@updateProfile')->name('update-profile');
        Route::get('/password',                                     'ProfileController@editPassword')->name('edit-password');
        Route::post('/password',                                    'ProfileController@updatePassword')->name('update-password');
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('users')->name('users/')->group(static function () {
            Route::get('/',                                             'UsersController@index')->name('index');
            Route::get('/create',                                       'UsersController@create')->name('create');
            Route::post('/',                                            'UsersController@store')->name('store');
            Route::get('/{user}/edit',                                  'UsersController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'UsersController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{user}',                                      'UsersController@update')->name('update');
            Route::delete('/{user}',                                    'UsersController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('cipher-keys')->name('cipher-keys/')->group(static function () {
            Route::get('/',                                             'CipherKeysController@index')->name('index');
            Route::get('/create',                                       'CipherKeysController@create')->name('create');
            Route::post('/',                                            'CipherKeysController@store')->name('store');
            Route::get('/{cipherKey}/edit',                             'CipherKeysController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'CipherKeysController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{cipherKey}',                                 'CipherKeysController@update')->name('update');
            Route::delete('/{cipherKey}',                               'CipherKeysController@destroy')->name('destroy');
            Route::post('/{cipherKey}/state',                               'CipherKeysController@changeState')->name('state');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('locations')->name('locations/')->group(static function() {
            Route::get('/',                                             'LocationsController@index')->name('index');
            Route::get('/create',                                       'LocationsController@create')->name('create');
            Route::post('/',                                            'LocationsController@store')->name('store');
            Route::get('/{location}/edit',                              'LocationsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'LocationsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{location}',                                  'LocationsController@update')->name('update');
            Route::delete('/{location}',                                'LocationsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('cipher-types')->name('cipher-types/')->group(static function() {
            Route::get('/',                                             'CipherTypesController@index')->name('index');
            Route::get('/create',                                       'CipherTypesController@create')->name('create');
            Route::post('/',                                            'CipherTypesController@store')->name('store');
            Route::get('/{cipherType}/edit',                            'CipherTypesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'CipherTypesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{cipherType}',                                'CipherTypesController@update')->name('update');
            Route::delete('/{cipherType}',                              'CipherTypesController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('key-types')->name('key-types/')->group(static function() {
            Route::get('/',                                             'KeyTypesController@index')->name('index');
            Route::get('/create',                                       'KeyTypesController@create')->name('create');
            Route::post('/',                                            'KeyTypesController@store')->name('store');
            Route::get('/{keyType}/edit',                               'KeyTypesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'KeyTypesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{keyType}',                                   'KeyTypesController@update')->name('update');
            Route::delete('/{keyType}',                                 'KeyTypesController@destroy')->name('destroy');
        });
    });
});