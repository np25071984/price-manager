<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('item/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }

    public function showPriceUploadForm()
    {
        return view('item/upload_form');
    }

    public function priceUpload(Request $request)
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

                $article = trim($worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue());
                $item = Item::where('article', $article)->first();
                if ($item) {
                    continue;
                }

                $brandName = trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
                $brand = Brand::where('name', $brandName)->first();
                if (!$brand) {
                    $brand = Brand::create([
                        'name' => $brandName
                    ]);
                }

                Item::create([
                    'brand_id' => $brand->id,
                    'article' => $article,
                    'name' => trim($worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue()),
                    'price' => trim($worksheet->getCellByColumnAndRow(5, $row)->getCalculatedValue()),
                    'stock' => trim($worksheet->getCellByColumnAndRow(8, $row)->getCalculatedValue()),
                ]);

                \DB::commit();
            } catch(PDOException $e) {
                \DB::rollback();
                throw $e;
            }

        }

        $request->session()->flash('message', 'Прайс успешно загружен!');

        return redirect(route('item.index'));
    }
}
