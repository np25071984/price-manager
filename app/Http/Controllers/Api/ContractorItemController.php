<?php

namespace App\Http\Controllers\Api;

use App\Contractor;
use App\ContractorItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\item\ContractorItemUnrelatedResourceCollection;
use App\Http\Resources\item\ContractorItemResourceCollection;
use App\Http\Resources\item\ContractorItemDeletedResourceCollection;

class ContractorItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Contractor $contractor)
    {
        $column = $request->input('column');
        if (!in_array($column, ['real_article', 'name', 'relation_name', 'price'])) {
            $column = 'name';
        }
        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $query = $request->input('q', null);
        $items = ContractorItem::smartSearch($query, $contractor->id)
            ->select([
                'contractor_items.id as id',
                'contractors.id as contractor_id',
                'contractors.name as contractor_name',
                \DB::raw("CASE WHEN json_typeof(contractors.config->'col_article') = 'null' THEN ''::varchar(32) ELSE contractor_items.article END as real_article"),
                'contractor_items.name as name',
                'contractor_items.price as price',
                'relations.id as relation_id',
                'items.id as relation_id',
                'items.name as relation_name',
            ])
            ->leftJoin('contractors', 'contractor_items.contractor_id', '=', 'contractors.id')
            ->leftJoin('relations', function($join) {
                $join->on('contractor_items.id', '=', 'relations.contractor_item_id');
                $join->on('contractor_items.contractor_id','=', 'relations.contractor_id');
            })
            ->leftJoin('items', 'relations.item_id', '=', 'items.id');

        if (!$query) {
            if ($column) {
                $items->orderBy($column, $order);
            } else {
                $items->orderBy($items->qualifyColumn('updated_at'), 'desc');
            }
        }

        $page = $request->input('page', 1);
        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ContractorItemResourceCollection($items);
    }

    public function contractorsItemsUnrelatedList(Request $request)
    {
        $column = $request->input('column');
        if (!in_array($column, ['contractor_name', 'real_article', 'name', 'price'])) {
            $column = null;
        }
        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $query = $request->input('q', null);
        $items = ContractorItem::smartSearch($query)
            ->select([
                'contractor_items.id as id',
                'contractors.id as contractor_id',
                'contractors.name as contractor_name',
                \DB::raw("CASE WHEN json_typeof(contractors.config->'col_article') = 'null' THEN ''::varchar(32) ELSE contractor_items.article END as real_article"),
                'contractor_items.name as name',
                'price',
            ])
            ->leftJoin('contractors', 'contractor_items.contractor_id', '=', 'contractors.id')
            ->unrelated();


        if (!$query) {
            if ($column) {
                $items->orderBy($column, $order);
            } else {
                $items->orderBy($items->qualifyColumn('updated_at'), 'desc');
            }
        }

        $page = $request->input('page', 1);
        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ContractorItemUnrelatedResourceCollection($items);
    }

    public function deletedItems(Request $request, Contractor $contractor)
    {
        $items = $contractor
            ->items()
            ->onlyTrashed()
            ->with('relatedItem');

        $column = $request->input('column');
        if (!in_array($column, ['real_article', 'name', 'relation_name', 'price'])) {
            $column = 'name';
        }

        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }
        $items->orderBy($column, $order);

        $query = $request->input('q', null);
        if ($query) {
            $items->where('name', 'ilike', "%{$query}%");
        }

        $page = $request->input('page', 1);
        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ContractorItemDeletedResourceCollection($items);
    }
}
