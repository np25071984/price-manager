<?php

namespace App\Http\Controllers\Api;

use App\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResourceCollection;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = Group::query();

        $query = $request->input('q', null);
        if ($query) {
            $groups->where('name', 'ilike', "%{$query}%");
        }

        $column = $request->input('column', 'name');
        if (!in_array($column, ['name'])) {
            $column = 'name';
        }

        $order = $request->input('order', 'asc');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $groups->orderby($column, $order);
        $page = $request->input('page', 1);

        $groups = $groups->paginate(30, ['*'], 'page', $page);

        return new GroupResourceCollection($groups);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return response()->json(null, 204);
    }
}
