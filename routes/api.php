<?php

use App\Brand;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('brand', 'Api\BrandController@index')->name('api.brand.index');
    Route::delete('brand/{brand}', 'Api\BrandController@destroy')->name('api.brand.destroy');

    Route::get('shop', 'Api\ShopController@index')->name('api.shop.index');
    Route::delete('shop/{shop}', 'Api\ShopController@destroy')->name('api.shop.destroy');

    Route::get('group', 'Api\GroupController@index')->name('api.group.index');
    Route::delete('group/{group}', 'Api\GroupController@destroy')->name('api.group.destroy');

    Route::get('item', 'Api\ItemController@index')->name('api.item.index');
    Route::delete('item/{item}', 'Api\ItemController@destroy')->name('api.item.destroy');
    Route::get('brand-item/{brand}', 'Api\ItemController@brandItems')->name('api.item.brand');
    Route::get('shop-item/{shop}', 'Api\ItemController@shopItems')->name('api.item.shop');
    Route::get('group-item/{group}', 'Api\ItemController@groupItems')->name('api.item.group');
    Route::get('item/{item}/related', 'Api\ItemController@relatedItems')->name('api.item.related');

    Route::get('contractor', 'Api\ContractorController@index')->name('api.contractor.index');
    Route::delete('contractor/{contractor}', 'Api\ContractorController@destroy')->name('api.contractor.destroy');

    Route::get('item/{contractor}/unrelated', 'Api\ItemController@indexUnrelated')->name('api.item.unrelated');

    Route::get('contractors-items', 'Api\ContractorItemController@contractorsItemsUnrelatedList')
        ->name('api.contractors-items.unrelated.index');

    Route::get('contractor-item/{contractor}', 'Api\ContractorItemController@index')
        ->name('api.contractor-item.index');

    Route::get('deleted-item/{contractor}', 'Api\ContractorItemController@deletedItems')
        ->name('api.contractor-item.deleted_items');

    Route::delete('/contractor/{item}/{contractorItem}/', 'Api\ContractorController@destroyRelation')->name('api.relation.destroy');
});

