<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\Http\Resources\ItemResouceCollection;
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
        if (!in_array($column, ['article', 'brand_name', 'item_name', 'price'])) {
            $column = 'item_name';
        }
        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $itemsWithoutRelation = \DB::table('items')
            ->select('items.id')
            ->leftJoin('relations', 'items.id', '=', 'relations.item_id')
            ->whereNull('relations.id');

        $items = Item::query()
            ->select([
                'items.id as item_id',
                'article',
                'brands.name as brand_name',
                'items.name as item_name',
                'price',
            ])
            ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
            ->wherein('items.id', $itemsWithoutRelation)
            ->orderBy($column, $order);

        $query = $request->input('q', null);
        if ($query) {
            $items->where('items.name', 'ilike', "%{$query}%");
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemResouceCollection($items);
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
    public function destroy($id)
    {
        //
    }
}
