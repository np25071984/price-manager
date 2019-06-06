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

    Route::resource('brand', 'BrandController')->except(['destroy']);

    Route::resources([
        'item' => 'ItemController',
        'contractor' => 'ContractorController',
    ]);

    // upload own price list
    Route::get('/upload', 'ItemController@showPriceUploadForm')->name('item.upload_form');
    Route::post('/upload', 'ItemController@priceUpload')->name('item.upload');

    // download own price list
    Route::get('/generate', 'ItemController@priceGenerate')->name('item.generate');
    Route::get('/download', 'ItemController@priceDownload')->name('item.download');

    // upload contractor`s price list
    Route::get('/contractor/{contractor}/deleted_items', 'ContractorController@deletedItems')->name('contractor.deleted_items');
    Route::get('/contractor/{contractor}/upload', 'ContractorController@showPriceUploadForm')->name('contractor.upload_form');
    Route::post('/contractor/{contractor}/upload', 'ContractorController@priceUpload')->name('contractor.upload');
    Route::get('/contractor/{contractor}/{contractorItem}/relation', 'ContractorController@showReationForm')->name('contractor.relation_form');
    Route::post('/contractor/{contractor}/{contractorItem}/relation', 'ContractorController@updateRelation')->name('contractor.relation_update');
    Route::delete('/contractor/{item}/{contractorItem}/', 'ContractorController@destroyRelation')->name('relation.destroy');
});

