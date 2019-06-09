<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\Brand;
use App\Http\Resources\ItemResouceCollection;
use App\Http\Resources\ItemBrandResourceCollection;
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
                'items.id as id',
                'article',
                'brands.name as brand_name',
                'items.name as item_name',
                'price',
                'stock',
            ])
            ->leftJoin('brands', 'items.brand_id', '=', 'brands.id');

        if ($column) {
            $items->orderBy($column, $order);
        } elseif (!$query) {
            $items->orderBy('items.updated_at', 'desc');
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
                'items.id as id',
                'article',
                'brands.name as brand_name',
                'items.name as item_name',
                'price',
            ])
            ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
            ->unrelated();

        if ($query && !is_numeric($query)) {
            $items->orderBy('rank', 'desc');
        } elseif ($column) {
            $items->orderBy($column, $order);
        } else {
            $items->orderBy('items.updated_at', 'desc');
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemUnrelatedResouceCollection($items);
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

    public function brandItems(Request $request, Brand $brand)
    {
        $column = $request->input('column');
        if (!in_array($column, ['article', 'name', 'price', 'stock'])) {
            $column = null;
        }
        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $items = Item::where(['brand_id' => $brand->id]);

        $query = $request->input('q', null);
        if ($query) {
            $items->where('name', 'ilike', "%{$query}%");
        }

        if ($column) {
            $items->orderBy($column, $order);
        } elseif (!$query) {
            $items->orderBy('items.updated_at', 'desc');
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemBrandResourceCollection($items);
    }
}
