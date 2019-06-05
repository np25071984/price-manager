<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Filesystem;

use App\JobStatus;
use App\Brand;
use App\Item;
use App\Jobs\ParsePrice;
use App\Jobs\GeneratePrice;
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
        if ($job && !$job->hasError()) {
            return view('price_processing_placeholder', [
                'job' => $job,
                'owner' => 'прайсом',
            ]);
        } else {
            $path = storage_path('prices/' . \Auth::id());
            $files = glob($path . '/*.xlsx');

            if (isset($files[0])) {
                $filesystem = new Filesystem;
                $pathinfo = pathinfo($files[0]);

                $price = sprintf("%s (%s Кб)", $pathinfo['basename'], ceil($filesystem->size($files[0])/1024));
            } else {
                $price = null;
            }

            if ($request->has('query')) {
                $query = $request->input('query');

                if (is_numeric($query)) {
                    /** reckon query string is an item article */
                    $items = Item::where(['article' => $query])->with('brand')->get();

                    $contractorItemsWithoutRelation = \DB::table('contractor_items')
                        ->select('contractor_items.id')
                        ->leftJoin('relations', 'contractor_items.id', '=', 'relations.contractor_item_id')
                        ->where(['contractor_items.article' => $query])
                        ->whereNull('relations.id');

                    $contractorItems = ContractorItem::whereIn('id', $contractorItemsWithoutRelation)->get();
                } else {
                    $query = trim(str_replace(['(', ')', '|', '&', '*'], ' ', $query));
                    $queryOrig = preg_replace('/\s+/u', ' ', $query);

                    /** extract digits from the query */
                    preg_match_all('/\d+/', $queryOrig, $matches);
                    $numbers = $matches[0];
                    $query = trim(preg_replace('/\d+/', '', $queryOrig));

                    /** extract russian words */
                    preg_match_all('/[А-Яа-яЁё]+/u', $query, $matches);
                    $russian = $matches[0];
                    $query = trim(preg_replace('/[А-Яа-яЁё]+/u', '', $query));

                    $russianQuery = null;
                    if (count($russian)) {
                        foreach ($russian as $key => $word) {
                            $russian[$key] = $word . ':*';
                        }
                        $russianQuery = implode(' & ', $russian);
                        unset($russian);
                    }

                    /** extract english words */
                    $english = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);

                    $englishQuery = null;
                    if (count($english)) {
                        foreach ($english as $key => $word) {
                            $english[$key] = $word . ':*';
                        }
                        $englishQuery = implode(' & ', $english);
                        unset($english);
                    }

                    $tsvItems = Item::query()->select([
                        '*',
                        \DB::raw("(ts_rank(tsvector_token, "
                            . "ts_rewrite(to_tsquery('english', coalesce(?, '')), 'SELECT t, s FROM aliases')) "
                            . "+ ts_rank(tsvector_token, "
                            . "ts_rewrite(to_tsquery('russian', coalesce(?, '')), 'SELECT t, s FROM aliases'))"
                            . ") as rank")]
                    )->setBindings([$englishQuery, $russianQuery]);
                    foreach ($numbers as $number) {
                        $tsvItems->where('name', 'like', "%{$number}%");
                    }
                    if ($russianQuery) {
                        $tsvItems->whereRaw("tsvector_token @@ ts_rewrite(to_tsquery('russian', ?), 'SELECT t, s FROM aliases')", [$russianQuery]);
                    }
                    if ($englishQuery) {
                        $tsvItems->whereRaw("tsvector_token @@ ts_rewrite(to_tsquery('english', ?), 'SELECT t, s FROM aliases')", [$englishQuery]);
                    }

                    $trgItems = Item::query()
                        ->select(['*', \DB::raw("similarity(name, ?) as rank")])
                        ->setBindings([$queryOrig]);
                    $trgItems->whereRaw('name % ?', [$queryOrig]);

                    $items = $tsvItems->union($trgItems)
                        ->orderBy('rank', 'desc')
                        ->paginate(30, ['*'], 'item-page');

                    /** Select only unbinded ContractorItem models */
                    $contractorItemsWithoutRelation = \DB::table('contractor_items')
                        ->select('contractor_items.id')
                        ->leftJoin('relations', 'contractor_items.id', '=', 'relations.contractor_item_id')
                        ->whereNull('relations.id');

                    $tsvContractorItems = ContractorItem::query()->select([
                        '*',
                        \DB::raw("(ts_rank(tsvector_token, "
                        . "ts_rewrite(to_tsquery('english', coalesce(?, '')), 'SELECT t, s FROM aliases')) "
                        . "+ ts_rank(tsvector_token, "
                        . "ts_rewrite(to_tsquery('russian', coalesce(?, '')), 'SELECT t, s FROM aliases'))"
                        . ") as rank")]
                    )->setBindings([$englishQuery, $russianQuery]);;
                    foreach ($numbers as $number) {
                        $tsvContractorItems->where('name', 'like', "%{$number}%");
                    }
                    if ($englishQuery) {
                        $tsvContractorItems->whereRaw("to_tsvector('english', name) @@ ts_rewrite(to_tsquery('english', ?), 'SELECT t, s FROM aliases')", [$englishQuery]);
                    }
                    if ($russianQuery) {
                        $tsvContractorItems->whereRaw("to_tsvector('russian', name) @@ ts_rewrite(to_tsquery('russian', ?), 'SELECT t, s FROM aliases')", [$russianQuery]);
                    }

                    $trgContractItems = ContractorItem::query()->select(['*', \DB::raw("similarity(name, ?) as rank")])->setBindings([$queryOrig]);
                    $trgContractItems->whereRaw('name % ?', [$queryOrig]);

                    $contractorItems = $tsvContractorItems->union($trgContractItems)
                        ->whereIn('id', $contractorItemsWithoutRelation)
                        ->orderBy('rank', 'desc')
                        ->paginate(30, ['*'], 'citem-page');
                }
            } else {
                $contractorItemsWithoutRelation = \DB::table('contractor_items')
                    ->select('contractor_items.id')
                    ->leftJoin('relations', 'contractor_items.id', '=', 'relations.contractor_item_id')
                    ->whereNull('relations.id');

                $contractorItems = ContractorItem::whereIn('id', $contractorItemsWithoutRelation)->paginate(30, ['*'], 'citem-page');

                $items = Item::paginate(30, ['*'], 'item-page');
            }

            if ($items instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $items->appends(request()->input())->appends(['active-tab' => 1]);
            }

            if ($contractorItems instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $contractorItems->appends(request()->input())->appends(['active-tab' => 2]);
            }
            return view('item/index', compact('items', 'contractorItems', 'job', 'price'));
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
        $path = storage_path('prices/' . \Auth::id());

        $files = glob($path . '/*.xlsx');

        if (!isset($files[0])) {
            abort(404);
        }

        return response()->download($files[0]);
    }

    public function priceGenerate(Request $request)
    {
        $path = storage_path('prices/' . \Auth::id());

        $filesystem = new Filesystem;
        if ($filesystem->exists($path)) {
            $filesystem->cleanDirectory($path);
        } else {
            $filesystem->makeDirectory($path);
        }

        $name = date("d-m-Y") . '-vozduhi.xlsx';

        GeneratePrice::dispatch($path . '/' . $name);

        $request->session()->flash('message', 'Запущен процесс генерации нового прайса!');

        return redirect(route('item.index'));
    }
}
