<?php

namespace App\Http\Controllers\Api;

use App\ContractorItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemContractorUnrelatedResourceCollection;

class ItemContractorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function contractorsItemsUnrelatedList(Request $request)
    {
        $column = $request->input('column');
        if (!in_array($column, ['contractor_name', 'real_article', 'item_name', 'price'])) {
            $column = null;
        }
        $order = $request->input('order');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $query = $request->input('q', null);
        $items = ContractorItem::smartSearch($query)
            ->select([
                'search_result.id as id',
                'contractor_id',
                'article',
                \DB::raw("CASE WHEN json_typeof(contractors.config->'col_article') = 'null' THEN ''::varchar(32) ELSE article END as real_article"),
                'contractors.name as contractor_name',
                'search_result.name as item_name',
                'price',
                'deleted_at',
            ])
            ->leftJoin('contractors', 'search_result.contractor_id', '=', 'contractors.id')
            ->unrelated('search_result');

        if ($column) {
            $items->orderBy($column, $order);
        } elseif (!$query) {
            $items->orderBy('search_result.updated_at', 'desc');
        }

        $page = $request->input('page', 1);

        $items = $items->paginate(30, ['*'], 'page', $page);

        return new ItemContractorUnrelatedResourceCollection($items);
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
