<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResourceCollection;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brands = Brand::query();

        $query = $request->input('q', null);
        if ($query) {
            $brands->where('name', 'ilike', "%{$query}%");
        }

        $column = $request->input('column', 'name');
        if (!in_array($column, ['name'])) {
            $column = 'name';
        }

        $order = $request->input('order', 'asc');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $brands->orderby($column, $order);
        $page = $request->input('page', 1);

        $brands = $brands->paginate(30, ['*'], 'page', $page);

        return new BrandResourceCollection($brands);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json(null, 204);
    }
}
