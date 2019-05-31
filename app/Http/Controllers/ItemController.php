<?php

namespace App\Http\Controllers;

use App\JobStatus;
use App\Brand;
use App\Item;
use App\Jobs\ParsePrice;
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
        $job = JobStatus::where(['contractor_id' => null])->first();
        if ($job && ($job->status_id !== 3)) {
            return view('price_processing_placeholder', [
                'job' => $job,
                'owner' => 'прайсом',
            ]);
        } else {
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

            return view('item/index', compact('items', 'contractorItems', 'job'));
        }
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
            $request->session()->flash('message', 'Не выбран файл для загрузки!');

            return redirect(route('item.index'));
        }

        $price = $request->file('price');
        $tmpName   = time() . '.' . $price->getClientOriginalExtension();
        $price->move(storage_path('tmp'), $tmpName);

        ParsePrice::dispatch(null, storage_path('tmp') . '/' . $tmpName);

        JobStatus::updateOrCreate(
            ['contractor_id' => null],
            [
                'status_id' => 1,
                'message' => 'Прайс успешно загружен',
            ]
        );

        $request->session()->flash('message', 'Прайс отправлен на обработку!');

        return redirect(route('main'));
    }

    public function priceDownload()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Артикул');
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->setCellValue('B1', 'Бренд');
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->setCellValue('C1', 'Наименование');
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->setCellValue('D1', 'Мин.цена');
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->setCellValue('E1', 'Цена');
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->setCellValue('F1', 'Остаток');
        $sheet->getColumnDimension('F')->setWidth(14);

        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getStyle('1:1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D:F')->getAlignment()->setHorizontal('center');

        foreach (Item::all() as $key => $item) {
            $row = $key + 2;

            $sheet->setCellValue('A' . $row, $item->article);
            $sheet->setCellValue('B' . $row, $item->brand->name);
            $sheet->setCellValue('C' . $row, $item->name);
            $sheet->setCellValue(
                'D' . $row,
                $item->contractorItems()->orderBy('price')->first()
                        ? $item->contractorItems()->orderBy('price')->first()->price
                        : ''
            );
            $sheet->setCellValue('E' . $row, $item->price);
            $sheet->setCellValue('F' . $row, $item->stock);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('price.xlsx');
        return response()->download('price.xlsx')->deleteFileAfterSend();
    }
}
