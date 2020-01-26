<?php

namespace App\Http\Controllers\Api;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\country\CountryResourceCollection;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $countries = Country::query();

        $query = $request->input('q', null);
        if ($query) {
            $countries->where('name', 'ilike', "%{$query}%");
        }

        $column = $request->input('column', 'name');
        if (!in_array($column, ['name'])) {
            $column = 'name';
        }

        $order = $request->input('order', 'asc');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $countries->orderby($column, $order);
        $page = $request->input('page', 1);

        $countries = $countries->paginate(30, ['*'], 'page', $page);

        return new CountryResourceCollection($countries);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        $country->delete();

        return response()->json(null, 204);
    }
}
