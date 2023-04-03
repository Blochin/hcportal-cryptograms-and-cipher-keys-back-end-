<?php

use App\Mail\UpdateCipherKeyStateMail;
use App\Models\CipherKey;
use App\Models\Cryptogram;
use Carbon\Carbon;
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
    //return new UpdateCipherKeyStateMail(CipherKey::first());

    // foreach ($cipherKeyByCentury as $key) {
    //     $date = $key->used_from ?: $key->used_to;
    //     $century = (string) ceil($date->year / 100);
    //     $centuries->push(['title' => $century . ". century", 'century_from' => $date->startOfCentury()->year, 'century_to' => $date->endOfCentury()->year]);
    // }

    // foreach ($cipherKeyByCentury as $key) {
    //     $date = $key->used_from ?: $key->used_to;
    //     $century = (string) ceil($date->year / 100);
    //     $centuries->push(['title' => $century . ". century", 'century_from' => $date->startOfCentury()->year, 'century_to' => $date->endOfCentury()->year]);
    // }

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
        Route::get('/pair-keys-cryptograms',                       'CryptogramsController@bulkPairKeysAndCryptograms')->name('pair-keys-cryptograms');
        Route::post('/pair-keys-cryptograms',                       'CryptogramsController@saveBulkPairKeysAndCryptograms')->name('save-pair-keys-cryptograms');
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
            Route::get('/search',                                 'CipherKeysController@search')->name('search');
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
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('locations')->name('locations/')->group(static function () {
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
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('cipher-types')->name('cipher-types/')->group(static function () {
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
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('key-types')->name('key-types/')->group(static function () {
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


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('cipher-key-similarities')->name('cipher-key-similarities/')->group(static function () {
            Route::get('/',                                             'CipherKeySimilaritiesController@index')->name('index');
            Route::get('/create',                                       'CipherKeySimilaritiesController@create')->name('create');
            Route::post('/',                                            'CipherKeySimilaritiesController@store')->name('store');
            Route::get('/{cipherKeySimilarity}/edit',                   'CipherKeySimilaritiesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'CipherKeySimilaritiesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{cipherKeySimilarity}',                       'CipherKeySimilaritiesController@update')->name('update');
            Route::delete('/{cipherKeySimilarity}',                     'CipherKeySimilaritiesController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('tags')->name('tags/')->group(static function () {
            Route::get('/',                                             'TagsController@index')->name('index');
            Route::get('/create',                                       'TagsController@create')->name('create');
            Route::post('/',                                            'TagsController@store')->name('store');
            Route::get('/{tag}/edit',                                   'TagsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'TagsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{tag}',                                       'TagsController@update')->name('update');
            Route::delete('/{tag}',                                     'TagsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('cryptograms')->name('cryptograms/')->group(static function () {
            Route::get('/',                                             'CryptogramsController@index')->name('index');
            Route::get('/create',                                       'CryptogramsController@create')->name('create');
            Route::post('/',                                            'CryptogramsController@store')->name('store');
            Route::get('/{cryptogram}/edit',                                'CryptogramsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'CryptogramsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{cryptogram}',                                    'CryptogramsController@update')->name('update');
            Route::delete('/{cryptogram}',                                  'CryptogramsController@destroy')->name('destroy');
            Route::post('/{cryptogram}/state',                               'CryptogramsController@changeState')->name('state');
            Route::get('/search',                                 'CryptogramsController@search')->name('search');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('solutions')->name('solutions/')->group(static function () {
            Route::get('/',                                             'SolutionsController@index')->name('index');
            Route::get('/create',                                       'SolutionsController@create')->name('create');
            Route::post('/',                                            'SolutionsController@store')->name('store');
            Route::get('/{solution}/edit',                              'SolutionsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'SolutionsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{solution}',                                  'SolutionsController@update')->name('update');
            Route::delete('/{solution}',                                'SolutionsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('people')->name('people/')->group(static function () {
            Route::get('/',                                             'PersonsController@index')->name('index');
            Route::get('/create',                                       'PersonsController@create')->name('create');
            Route::post('/',                                            'PersonsController@store')->name('store');
            Route::get('/{person}/edit',                                'PersonsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PersonsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{person}',                                    'PersonsController@update')->name('update');
            Route::delete('/{person}',                                  'PersonsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('languages')->name('languages/')->group(static function () {
            Route::get('/',                                             'LanguagesController@index')->name('index');
            Route::get('/create',                                       'LanguagesController@create')->name('create');
            Route::post('/',                                            'LanguagesController@store')->name('store');
            Route::get('/{language}/edit',                              'LanguagesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'LanguagesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{language}',                                  'LanguagesController@update')->name('update');
            Route::delete('/{language}',                                'LanguagesController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('categories')->name('categories/')->group(static function () {
            Route::get('/',                                             'CategoriesController@index')->name('index');
            Route::get('/create',                                       'CategoriesController@create')->name('create');
            Route::post('/',                                            'CategoriesController@store')->name('store');
            Route::get('/{category}/edit',                              'CategoriesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'CategoriesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{category}',                                  'CategoriesController@update')->name('update');
            Route::delete('/{category}',                                'CategoriesController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::prefix('digitalized-transcriptions')->name('digitalized-transcriptions/')->group(static function () {
            Route::get('/',                                             'DigitalizedTranscriptionsController@index')->name('index');
            Route::get('/create',                                       'DigitalizedTranscriptionsController@create')->name('create');
            Route::post('/',                                            'DigitalizedTranscriptionsController@store')->name('store');
            Route::get('/{digitalizedTranscription}/edit',              'DigitalizedTranscriptionsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'DigitalizedTranscriptionsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{digitalizedTranscription}',                  'DigitalizedTranscriptionsController@update')->name('update');
            Route::delete('/{digitalizedTranscription}',                'DigitalizedTranscriptionsController@destroy')->name('destroy');
        });
    });
});
