<?php

namespace App\Http\Controllers\Api;

use App\Aroma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AromaResourceCollection;

class AromaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $aromas = Aroma::query();

        $query = $request->input('q', null);
        if ($query) {
            $aromas->where('name', 'ilike', "%{$query}%");
        }

        $column = $request->input('column', 'name');
        if (!in_array($column, ['name'])) {
            $column = 'name';
        }

        $order = $request->input('order', 'asc');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $aromas->orderby($column, $order);
        $page = $request->input('page', 1);

        $aromas = $aromas->paginate(30, ['*'], 'page', $page);

        return new AromaResourceCollection($aromas);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aroma $aroma)
    {
        $aroma->delete();

        return response()->json(null, 204);
    }
}
