<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\Brand;
use App\Group;
use App\Contractor;
use App\Http\Resources\ItemResouceCollection;
use App\Http\Resources\ItemBrandResourceCollection;
use App\Http\Resources\ItemUnrelatedResouceCollection;
use App\Http\Resources\ItemRelatedResourceCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * List of user items
     *
     * @param Request $request
     * @return ItemResouceCollection
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

        if (!$query) {
            if ($column) {
                $items->orderBy($column, $order);
            } else {
                $items->orderBy('items.updated_at', 'desc');
            }
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemResouceCollection($items);
    }

    /**
     * List of user unrelated items
     *
     * @param Request $request
     * @return ItemResouceCollection
     */
    public function indexUnrelated(Request $request, Contractor $contractor)
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
            ->unrelated($contractor->id);

        if (!$query) {
            if ($column) {
                $items->orderBy($column, $order);
            } else {
                $items->orderBy('items.updated_at', 'desc');
            }
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemUnrelatedResouceCollection($items);
    }

    /**
     * List of related items
     *
     * @param Request $request
     * @param Item $item
     * @return ItemResouceCollection
     */
    public function relatedItems(Request $request, Item $item)
    {
        $column = $request->input('column');
        if (!in_array($column, ['contractor', 'name', 'price'])) {
            $column = 'price';
        }
        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $items = $item->contractorItems()
            ->select([
                'contractor_items.*',
                'contractors.name as contractor',
            ])
            ->leftJoin('contractors', 'contractor_items.contractor_id', '=', 'contractors.id');

        $query = $request->input('q', null);
        if ($query) {
            $items->where('contractor_items.name', 'ilike', "%{$query}%");
        }

        if ($column) {
            $items->orderBy($column, $order);
        } else {
            $items->orderBy('items.updated_at', 'desc');
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemRelatedResourceCollection($items);
    }

    /**
     * Remove the specified item from storage
     *
     * @param Item $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }

    /**
     * List of brand items
     *
     * @param Request $request
     * @param Brand $brand
     * @return ItemBrandResourceCollection
     */
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

        if (!$query) {
            if ($column) {
                $items->orderBy($column, $order);
            } else {
                $items->orderBy('updated_at', 'desc');
            }
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemBrandResourceCollection($items);
    }

    /**
     * List of group items
     *
     * @param Request $request
     * @param Brand $group
     * @return ItemBrandResourceCollection
     */
    public function groupItems(Request $request, Group $group)
    {
        $column = $request->input('column');
        if (!in_array($column, ['article', 'name', 'price', 'stock'])) {
            $column = null;
        }
        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $items = Item::where(['group_id' => $group->id]);

        $query = $request->input('q', null);
        if ($query) {
            $items->where('name', 'ilike', "%{$query}%");
        }

        if (!$query) {
            if ($column) {
                $items->orderBy($column, $order);
            } else {
                $items->orderBy('updated_at', 'desc');
            }
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemBrandResourceCollection($items);
    }
}
