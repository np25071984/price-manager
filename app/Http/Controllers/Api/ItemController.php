<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\Http\Resources\ItemResouceCollection;
use App\Http\Resources\ItemUnrelatedResouceCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $column = $request->input('column');
        if (!in_array($column, ['article', 'brand_name', 'item_name', 'price', 'stock'])) {
            $column = null;
        }
        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $query = $request->input('q', null);
        $items = Item::smartSearch($query)
            ->select([
                'search_result.id as id',
                'article',
                'brands.name as brand_name',
                'search_result.name as item_name',
                'price',
                'stock',
            ])
            ->leftJoin('brands', 'search_result.brand_id', '=', 'brands.id');

        if ($column) {
            $items->orderBy($column, $order);
        } elseif (!$query) {
            $items->orderBy('search_result.updated_at', 'desc');
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemResouceCollection($items);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUnrelated(Request $request)
    {
        $column = $request->input('column');
        if (!in_array($column, ['article', 'brand_name', 'item_name', 'price'])) {
            $column = null;
        }
        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $query = $request->input('q', null);
        $items = Item::smartSearch($query)
            ->select([
                'search_result.id as id',
                'article',
                'brands.name as brand_name',
                'search_result.name as item_name',
                'price',
            ])
            ->leftJoin('brands', 'search_result.brand_id', '=', 'brands.id')
            ->unrelated('search_result');

        if ($column) {
            $items->orderBy($column, $order);
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemUnrelatedResouceCollection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
