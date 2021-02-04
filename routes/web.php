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

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function () {

    // Authentication Routes
    \Illuminate\Support\Facades\Auth::routes(['verify' => true, 'register' => false]);


    // Authorize Routes
    Route::group(['middleware' => ['authorize', 'unauthorized']], function () {
        // Only unauthorized users (with given ip, location and browser info) can access to this routes, authorized users do not need to authorize their accounts so they can't access to this routes.
        Route::get('authorize', 'App\Http\Controllers\Auth\AuthorizeController@index')->name('authorize');
        Route::get('authorize/{token}', 'App\Http\Controllers\Auth\AuthorizeController@verify')->name('authorize.verify');
        Route::post('authorize/resend', 'App\Http\Controllers\Auth\AuthorizeController@resend')->name('authorize.resend');
    });


    // Admin Authenticated users route group
    Route::group(['prefix' => 'admin', 'middleware' => ['authorize', 'auth', 'verified', 'online', 'manager'], 'namespace' => 'App\Http\Controllers\Admin'], function () {
        Route::get('/', 'HomeController@index')->name('admin.home');

        //Route::get('local/{folders}/{hash}.{extension}', 'HomeController@privateStorage')->where('hash', '[a-zA-Z0-9-.]+')->where('extension', '[css, js, jpg, jpeg, gif, png, mp4, bmp]+');

        // Only developer routes
        Route::group(['prefix' => 'dev', 'middleware' => 'dev', 'namespace' => 'Dev'], function () {

            // Dev/Permissions
            Route::prefix('permissions')->group(function () {
                Route::get('/', 'PermissionController@index')->name('permissions.index');

                // Only for ajax operations
                Route::middleware('only.ajax')->group(function () {
                    Route::get('ajax', 'PermissionController@ajaxList')->name('permissions.ajax');
                    Route::get('checkboxes', 'PermissionController@checkboxesView')->name('permissions.checkboxes');

                    Route::post('/', 'PermissionController@create')->name('permissions.create');
                    Route::get('{id}', 'PermissionController@find')->name('permissions.find');
                    Route::put('{id}', 'PermissionController@update')->name('permissions.update');
                    Route::delete('{id}', 'PermissionController@destroy')->name('permissions.destroy');
                });
            });

            // Dev/Config
            Route::prefix('config')->group(function () {
                Route::get('/', 'ConfigController@index')->name('config.index');

                // Only for ajax operations
                Route::middleware('only.ajax')->group(function () {
                    // I write this as a post request because we send a file path (route library search it like it is a route)
                    Route::post('find', 'ConfigController@find')->name('config.find');
                    Route::put('update', 'ConfigController@update')->name('config.update');
                });
            });
        });

        // Files
        Route::prefix('files')->group(function () {
            Route::post('/', 'FileController@upload')->name('files.upload');
            Route::get('{id}', 'FileController@download')->name('files.download');

            // Only for ajax operations
            Route::middleware('only.ajax')->group(function () {
                Route::put('save-sequence', 'FileController@saveSequence')->name('files.save_sequence');

                Route::get('find/{id}', 'FileController@find')->name('files.find');
                Route::put('{id}', 'FileController@update')->name('files.update');
                Route::delete('{id}', 'FileController@destroy')->name('files.destroy');
            });
        });

        // User profile page
        Route::prefix('profile')->group(function () {
            Route::get('/', 'ProfileController@index')->name('profile.index');
            Route::get('reset-password', 'ProfileController@requestPassword')->name('profile.reset_password');

            // Only for ajax operations
            Route::middleware('only.ajax')->group(function () {
                Route::put('/', 'ProfileController@update')->name('profile.update');
            });
        });

        // Settings page
        Route::prefix('settings')->group(function () {
            Route::get('/', 'SettingController@index')->name('settings.index');

            // Only for ajax operations
            Route::middleware('only.ajax')->group(function () {
                Route::put('/', 'SettingController@update')->name('settings.update');
            });
        });

        // Users page
        Route::prefix('users')->group(function () {
            Route::get('/', 'UserController@index')->name('users.index');

            // Only for ajax operations
            Route::middleware('only.ajax')->group(function () {
                Route::get('datatable', 'UserController@datatable')->name('users.datatable');
                Route::post('/', 'UserController@create')->name('users.create');
                Route::get('{id}', 'UserController@find')->name('users.find');
                Route::put('{id}', 'UserController@update')->name('users.update');
                Route::delete('{id}', 'UserController@destroy')->name('users.destroy');
                Route::get('restore/{id}', 'UserController@restore')->name('users.restore');
            });
        });

        // Menus page
        Route::group(['prefix' => 'menus', 'namespace' => 'Menu'], function () {
            Route::get('/', 'GroupController@index')->name('menus.index');

            // Only for ajax operations
            Route::middleware('only.ajax')->group(function () {
                Route::get('datatable', 'GroupController@datatable')->name('menus.datatable');
                Route::post('/', 'GroupController@create')->name('menus.create');
                Route::get('{id}', 'GroupController@find')->name('menus.find');
                Route::put('{id}', 'GroupController@update')->name('menus.update');
                Route::delete('{id}', 'GroupController@destroy')->name('menus.destroy');
                Route::get('restore/{id}', 'GroupController@restore')->name('menus.restore');
            });

            Route::prefix('{groupId}/items')->group(function () {
                Route::get('/', 'ItemController@index')->name('menu_items.index');

                // Only for ajax operations
                Route::middleware('only.ajax')->group(function () {
                    Route::put('save-sequence', 'ItemController@saveSequence')->name('menu_items.save_sequence');
                    Route::get('ajax', 'ItemController@ajaxList')->name('menu_items.ajax');

                    Route::post('/', 'ItemController@create')->name('menu_items.create');
                    Route::get('{id}', 'ItemController@find')->name('menu_items.find');
                    Route::put('{id}', 'ItemController@update')->name('menu_items.update');
                    Route::delete('{id}', 'ItemController@destroy')->name('menu_items.destroy');
                    Route::get('restore/{id}', 'ItemController@restore')->name('menu_items.restore');
                });
            });
        });

        // Posts page
        Route::prefix('posts')->group(function () {
            Route::get('/', 'PostController@index')->name('posts.index');
            Route::get('sort', 'PostController@sort')->name('posts.sort');

            // Only for ajax operations
            Route::middleware('only.ajax')->group(function () {
                Route::put('save-sequence', 'PostController@saveSequence')->name('posts.save_sequence');

                Route::get('datatable', 'PostController@datatable')->name('posts.datatable');

                Route::post('/', 'PostController@create')->name('posts.create');
                Route::get('{id}', 'PostController@find')->name('posts.find');
                Route::put('{id}', 'PostController@update')->name('posts.update');
                Route::delete('{id}', 'PostController@destroy')->name('posts.destroy');
                Route::get('restore/{id}', 'PostController@restore')->name('posts.restore');
            });
        });

    });

    // Website routes with locale prefix
    Route::group(['prefix' => '/', 'namespace' => 'App\Http\Controllers\Site'], function () {
        Route::get('/', 'WebController@index')->name('web.index');

        Route::get(LaravelLocalization::transRoute('routes.posts'), 'WebController@postList');
        Route::get(LaravelLocalization::transRoute('routes.profile'), 'AuthController@profile')->name('web.profile');

        Route::get('t/{tag}', 'WebController@searchTag');
        Route::get('{url}', 'WebController@resolve');
    });
});

// Website routes without locale prefix
Route::group(['prefix' => '/_', 'namespace' => 'App\Http\Controllers\Site'], function () {
    Route::prefix('auth')->group(function () {
        Route::get('login', 'AuthController@loginView');
        Route::get('register', 'AuthController@registerView');

        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');

        Route::post('update-profile', 'AuthController@updateProfile')->name('web.update_profile');
    });

    Route::get('contact', 'WebController@contactView');
    Route::post('contact', 'WebController@contact');

    Route::post('comment/send', 'WebController@comment')->name('web.comment')->middleware('auth')->middleware('throttle:3,10');
    Route::post('vote', 'WebController@vote')->name('web.vote')->middleware('auth')->middleware('throttle:10,10');
    Route::post('save-post', 'WebController@savePost')->name('web.save_post')->middleware('auth')->middleware('throttle:10,10');

    Route::get('search/{q?}', 'WebController@search')->name('web.search');

});
