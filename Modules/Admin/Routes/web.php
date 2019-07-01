<?php

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

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
    ],
    function () {
        Route::get('/login', 'LoginController@showLoginForm')->name('login.get');
        Route::post('/login', 'LoginController@login')->name('login.post');
        Route::get('/logout', 'LoginController@logout')->name('logout');

        // ToDo: make auth by role.
        Route::middleware(['auth'])->group(function () {
            Route::get('/', 'DashboardController@index');

            Route::resource('roles', 'RoleController')->except(['show']);
            Route::patch('/users/{user}/activate', 'UserController@activate')->name('user.activate');
            Route::patch('/users/{user}/block', 'UserController@block')->name('user.block');
            Route::get('/users/search', 'UserController@search')->name('user.search');
            Route::resource('users', 'UserController')->except(['show']);
            Route::resource('locations', 'LocationController')->except(['show']);
            Route::resource('categories', 'CategoryController')->except(['show']);
            Route::get('/categories/{category}/attributes', 'CategoryController@attributes');
            Route::patch('/products/{product}/activate', 'ProductController@activate')->name('product.activate');
            Route::patch('/products/{product}/block', 'ProductController@block')->name('product.block');
            Route::get('/products/images/{id?}', 'ProductController@images');
            Route::resource('attributes', 'AttributeController')->except(['show']);
            Route::resource('currencies', 'CurrencyController')->except(['show']);
            Route::resource('products', 'ProductController')->except(['show']);
            Route::resource('settings', 'SettingController')->except(['show']);
        });
    }
);
