<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Item;
use App\ContractorItem;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('query')) {
            $query = $request->input('query');
            $items = Item::where('name', 'ilike', "%{$query}%")->paginate(30);

            $contractorItemsWithoutRelation = \DB::table('contractor_items')
                ->select('contractor_items.id')
                ->leftJoin('relations', 'contractor_items.id', '=', 'relations.contractor_item_id')
                ->where('contractor_items.name', 'ilike', "%{$query}%")
                ->whereNull('relations.id');

            $contractorItems = ContractorItem::whereIn('id', $contractorItemsWithoutRelation)->limit(30)->get();
        } else {
            $contractorItems = [];
            $items = Item::paginate(30);
        }
        return view('item/index', compact('items', 'contractorItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        return view('item/create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = Item::create([
            'brand_id' => $request->brand_id,
            'article' => $request->article,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        $request->session()->flash('message', 'Новый товар успешно добавлен!');

        return redirect(route('item.show' , $item->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('item/show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $brands = Brand::all();
        return view('item/edit', compact('item', 'brands'));
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
        $item->brand_id = $request->brand_id;
        $item->article = $request->article;
        $item->name = $request->name;
        $item->price = $request->price;
        $item->stock = $request->stock;
        $item->save();

        $request->session()->flash('message', 'Товар успешно обновлен!');

        return redirect(route('item.show' , $item->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Item $item)
    {
        $name = $item->name;

        $item->delete();

        $request->session()->flash('message', "Товар '{$name}' успешно удален!");

        return redirect(route('item.index'));
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

                $brandName = trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
                $brand = Brand::where('name', $brandName)->first();
                if (!$brand) {
                    $brand = Brand::create([
                        'name' => $brandName
                    ]);
                }

                $article = trim($worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue());
                $item = Item::where('article', $article)->first();

                $name = trim($worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue());
                $price = trim($worksheet->getCellByColumnAndRow(5, $row)->getCalculatedValue());
                $stock = trim($worksheet->getCellByColumnAndRow(8, $row)->getCalculatedValue());

                if ($item) {
                    $item->brand_id = $brand->id;
                    $item->name = $name;
                    $item->price = $price;
                    $item->stock = $stock;
                    $item->save();
                } else {
                    Item::create([
                        'brand_id' => $brand->id,
                        'article' => $article,
                        'name' => $name,
                        'price' => $price,
                        'stock' => $stock,
                    ]);
                }

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
