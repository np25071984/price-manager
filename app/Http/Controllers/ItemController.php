<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Filesystem;

use App\Brand;
use App\Country;
use App\Shop;
use App\ShopItem;
use App\Group;
use App\Item;
use App\Jobs\ParsePrice;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;

class ItemController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemApiLink = route('api.item.index');
        $contractorApiLink = route('api.contractors-items.unrelated.index');

        return view('item/index', compact('job', 'itemApiLink', 'contractorApiLink'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::orderBy('name', 'asc')->get();
        $countries = Country::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        $groups = Group::orderBy('name', 'asc')->get();
        $types = Item::getTypes();
        return view('item/create', compact('brands', 'countries', 'groups', 'shops', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $item = \DB::transaction(function() use ($request) {
            $item = Item::create([
                'brand_id' => $request->brand_id,
                'country_id' => $request->country_id,
                'group_id' => $request->group_id,
                'article' => $request->article,
                'type' => $request->type,
                'name' => $request->name,
                'stock' => $request->stock,
            ]);

            if ($request->shop_id) {
                foreach ($request->shop_id as $shopId) {
                    ShopItem::create([
                        'item_id' => $item->id,
                        'shop_id' => $shopId,
                    ]);
                }
            }

            return $item;
        });

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
        $apiLink = route('api.item.related', [$item->id]);

        return view('item/show', compact('item', 'apiLink'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $shops = Shop::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        $countries = Country::orderBy('name', 'asc')->get();
        $groups = Group::orderBy('name', 'asc')->get();
        $types = Item::getTypes();
        return view('item/edit', compact('item', 'shops', 'brands', 'countries', 'groups', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, Item $item)
    {
        $item = \DB::transaction(function() use ($request, $item) {
            $item->brand_id = $request->brand_id;
            $item->country_id = $request->country_id;
            $item->article = $request->article;
            $item->type = $request->type;
            $item->name = $request->name;
            $item->stock = $request->stock;

            ShopItem::query()->where([
                'item_id' => $item->id,
            ])->delete();
            if ($request->shop_id) {
                foreach ($request->shop_id as $shopId) {
                    ShopItem::create([
                        'item_id' => $item->id,
                        'shop_id' => $shopId,
                    ]);
                }
            }

            return $item;
        });

        $item->save();

        $request->session()->flash('message', 'Товар успешно обновлен!');

        return redirect(route('item.show' , $item->id));
    }

    public function priceUploadForm()
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

        ParsePrice::dispatch(\Auth::id(), null, storage_path('tmp') . '/' . $tmpName)
            ->onQueue('price_list');

        PriceProcessingJobStatus::updateOrCreate(
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
        $path = storage_path('prices/');

        $filesystem = new Filesystem;
        if ($filesystem->exists($path)) {
            $filesystem->cleanDirectory($path);
        } else {
            $filesystem->makeDirectory($path);
        }

        $name = date("d-m-Y") . '-vozduhi.xlsx';

        GeneratePrice::dispatch(\Auth::id(), $path . '/' . $name);

        $request->session()->flash('message', 'Запущен процесс генерации нового прайса!');

        return redirect(route('item.index'));
    }
}
