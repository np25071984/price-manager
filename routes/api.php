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

Route::get('brand', 'Api\BrandController@index')->name('api.brand.index');
Route::delete('brand/{brand}', 'Api\BrandController@destroy')->name('api.brand.destroy');

Route::get('item', 'Api\ItemController@index')->name('api.item.index');