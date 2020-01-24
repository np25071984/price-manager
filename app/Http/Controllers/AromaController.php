<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aroma;
use App\Http\Requests\AromaRequest;

class AromaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiLink = route('api.aroma.index');
        return view('aroma/index', compact('apiLink'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('aroma/create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AromaRequest $request)
    {
        Aroma::create([
            'name' => $request->name,
        ]);

        $request->session()->flash('message', 'Новый аромат успешно добавлен!');

        return redirect(route('aroma.index'));
    }
}
