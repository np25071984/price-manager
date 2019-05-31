<?php

namespace App\Http\Controllers;

use App\JobStatus;
use App\Item;
use App\Relation;
use App\Contractor;
use App\ContractorItem;
use Illuminate\Http\Request;
use App\Jobs\ParsePrice;

class ContractorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contractors = Contractor::paginate(30);
        return view('contractor/index', compact('contractors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contractor/create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contractor = Contractor::create([
            'name' => $request->name,
        ]);

        $request->session()->flash('message', 'Новый поставщик успешно добавлен!');

        return redirect(route('contractor.show' , $contractor->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contractor  $contractor
     * @return \Illuminate\Http\Response
     */
    public function show(Contractor $contractor)
    {
        if ($contractor->job) {
            return view('price_processing_placeholder', [
                'job' => $contractor->job,
                'owner' => $contractor->name,
            ]);
        } else {
            $items = ContractorItem::where(['contractor_id' => $contractor->id])->paginate(30);

            return view('contractor/show', compact('contractor', 'items'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contractor  $contractor
     * @return \Illuminate\Http\Response
     */
    public function edit(Contractor $contractor)
    {
        return view('contractor/edit', compact('contractor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contractor  $contractor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contractor $contractor)
    {
        $contractor->name = $request->name;
        $contractor->save();

        $request->session()->flash('message', 'Поставщик успешно обновлен!');

        return redirect(route('contractor.show' , $contractor->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contractor  $contractor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Contractor $contractor)
    {
        $name = $contractor->name;

        $contractor->delete();

        $request->session()->flash('message', "Поставщик '{$name}' успешно удален!");

        return redirect(route('contractor.index'));
    }

    public function showPriceUploadForm(Contractor $contractor)
    {
        return view('contractor/upload_form', compact('contractor'));
    }

    public function priceUpload(Request $request, Contractor $contractor)
    {
        if (!$request->hasFile('price')) {
            abort(404);
        }

        $price = $request->file('price');
        $tmpName   = time() . '.' . $price->getClientOriginalExtension();
        $price->move(storage_path('tmp'), $tmpName);

        ParsePrice::dispatch($contractor->id, storage_path('tmp') . '/' . $tmpName);

        JobStatus::updateOrCreate(
            ['contractor_id' => $contractor->id],
            [
                'status_id' => 1,
                'message' => 'Прайс успешно загружен',
            ]
        );

        $request->session()->flash('message', 'Прайс поставщика успешно загружен!');

        return redirect(route('contractor.index'));
    }

    public function showReationForm(Contractor $contractor, ContractorItem $contractorItem)
    {

        $items = Item::paginate(30);

        return view('contractor/relation_form', compact('contractor', 'contractorItem', 'items'));
    }

    public function updateRelation(Request $request, Contractor $contractor, ContractorItem $contractorItem)
    {
        $item = $contractorItem->relatedItem->first();
        if ($item) {
            $relation = Relation::where([
                'item_id' => $item->id,
                'contractor_item_id' => $contractorItem->id,
            ])->first();
            $relation->item_id = $request->item;
            $relation->save();
        } else {
            Relation::create([
                'item_id' => $request->item,
                'contractor_item_id' => $contractorItem->id,
            ]);
        }

        $request->session()->flash('message', 'Связь успешно обновлена!');

        return redirect(route('contractor.show', $contractor->id));
    }
}
