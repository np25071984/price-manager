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
    Route::resource('item', 'ItemController')->except(['destroy']);
    Route::resource('contractor', 'ContractorController')->except(['destroy']);
    Route::resource('group', 'GroupController')->except(['destroy']);

    // upload own price list
    Route::get('upload', 'ItemController@priceUploadForm')->name('item.upload_form');
    Route::post('upload', 'ItemController@priceUpload')->name('item.upload');

    // download own price list
    Route::get('generate', 'ItemController@priceGenerate')->name('item.generate');
    Route::get('download', 'ItemController@priceDownload')->name('item.download');

    // upload contractor`s price list
    Route::get('contractor/{contractor}/deleted-items', 'ContractorController@deletedItems')->name('contractor.deleted_items');
    Route::get('contractor/{contractor}/upload', 'ContractorController@showPriceUploadForm')->name('contractor.upload_form');
    Route::post('contractor/{contractor}/upload', 'ContractorController@priceUpload')->name('contractor.upload');
    Route::get('contractor/{contractor}/{contractorItem}/relation', 'ContractorController@showReationForm')->name('contractor.relation_form');
    Route::post('contractor/{contractor}/{contractorItem}/relation', 'ContractorController@updateRelation')->name('contractor.relation_update');

});
