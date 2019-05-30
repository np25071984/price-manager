<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Item;
use App\Relation;
use App\Contractor;
use App\ContractorItem;
use Illuminate\Http\Request;

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

        $items = ContractorItem::where(['contractor_id' => $contractor->id])->paginate(30);

        return view('contractor/show', compact('contractor', 'items'));
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

        /** Create a new Xls Reader  **/
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

        $spreadsheet = $reader->load($price->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {

            try {
                \DB::beginTransaction();

                $name = trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
                $price = trim($worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue());

                $contractorItem = ContractorItem::where([
                    'contractor_id' => $contractor->id,
                    'name' => $name,
                ])->first();

                if ($contractorItem) {
                    $contractorItem->contractor_id = $contractor->id;
                    $contractorItem->name = $name;
                    $contractorItem->price = $price;
                    $contractorItem->save();
                } else {
                    ContractorItem::create([
                        'contractor_id' => $contractor->id,
                        'name' => $name,
                        'price' => $price,
                    ]);
                }

                \DB::commit();
            } catch(PDOException $e) {
                \DB::rollback();
                throw $e;
            }
        }

        $request->session()->flash('message', 'Прайс поставщика успешно загружен!');

        return redirect(route('contractor.show', $contractor->id));
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
