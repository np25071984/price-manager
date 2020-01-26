<?php

namespace App\Http\Controllers\Api;

use App\Contractor;
use App\Item;
use App\ContractorItem;
use App\Relation;
use App\Http\Resources\item\ContractorResourceCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContractorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contractors = Contractor::query();

        $query = $request->input('q', null);
        if ($query) {
            $contractors->where('name', 'ilike', "%{$query}%");
        }

        $column = $request->input('column', 'name');
        if (!in_array($column, ['name'])) {
            $column = 'name';
        }

        $order = $request->input('order', 'asc');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $contractors->orderby($column, $order);
        $page = $request->input('page', 1);

        $contractors = $contractors->paginate(30, ['*'], 'page', $page);

        return new ContractorResourceCollection($contractors);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contractor $contractor)
    {
        $contractor->delete();

        return response()->json(null, 204);
    }

    public function destroyRelation(Item $item, ContractorItem $contractorItem)
    {
        Relation::where([
            'item_id' => $item->id,
            'contractor_item_id' => $contractorItem->id,
        ])->delete();

        return response()->json(null, 204);
    }
}
