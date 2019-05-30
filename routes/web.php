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

Auth::routes();


Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'MainController@index')->name('main');

    Route::resources([
        'brand' => 'BrandController',
        'item' => 'ItemController',
        'contractor' => 'ContractorController',
    ]);

    // upload own price list
    Route::get('/price_upload', 'ItemController@showPriceUploadForm')->name('item.price_upload_form');
    Route::post('/price_upload', 'ItemController@priceUpload')->name('item.price_upload');

    // upload contractor`s price list
    Route::get('/contractor/{contractor}/price_upload', 'ContractorController@showPriceUploadForm')->name('contractor.price_upload_form');
    Route::post('/contractor/{contractor}/price_upload', 'ContractorController@priceUpload')->name('contractor.price_upload');

});

