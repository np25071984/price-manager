<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use App\Jobs\GeneratePrice;
use App\Shop;
use App\ShopItem;
use App\Item;
use App\PriceGenerationStatus;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiLink = route('api.shop.index');
        return view('shop/index', compact('apiLink'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shop/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shop = Shop::create([
            'name' => $request->name,
            'url' => $request->url,
        ]);

        $request->session()->flash('message', 'Новый магазин успешно добавлен!');

        return redirect(route('shop.show', $shop->id));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        $job = PriceGenerationStatus::where(['shop_id' => $shop->id])->first();
        $path = storage_path('prices/' . $shop->id);
        $files = glob($path . '/*.xlsx');

        if (isset($files[0])) {
            $filesystem = new Filesystem;
            $pathinfo = pathinfo($files[0]);

            $price = sprintf("%s (%s Кб)", $pathinfo['basename'], ceil($filesystem->size($files[0]) / 1024));
        } else {
            $price = null;
        }

        $apiLink = route('api.item.shop', [$shop->id]);

        return view('shop/show', compact('shop', 'job', 'price', 'apiLink'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop)
    {
        return view('shop/edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shop $shop)
    {
        $shop->name = $request->name;
        $shop->url = $request->url;
        $shop->save();

        $request->session()->flash('message', 'Магазин успешно обновлен!');

        return redirect(route('shop.show', $shop->id));
    }

    public function priceGenerate(Request $request, Shop $shop)
    {
        $shopId = $shop->id;
        $path = storage_path('prices/' . $shopId);

        $filesystem = new Filesystem;
        if ($filesystem->exists($path)) {
            $filesystem->cleanDirectory($path);
        } else {
            $filesystem->makeDirectory($path);
        }

        $name = date("d-m-Y") . '-' . Str::slug($shop->name, '-') . '.xlsx';

        GeneratePrice::dispatch($shopId, $path . '/' . $name)->onQueue('pricelist_generation');

        PriceGenerationStatus::create([
            'shop_id' => $shopId,
            'status_id' => 1,
            'message' => 'Прайс успешно загружен',
        ]);

        $request->session()->flash('message', 'Запущен процесс генерации нового прайса!');

        return redirect(route('shop.show', $shopId));
    }

    public function editItem(ShopItem $shopItem)
    {
        $shop = $shopItem->shop;
        $item = $shopItem->item;

        return view('shopitem/shop-item-edit', compact('shop', 'item', 'shopItem'));
    }

    public function updateItem(Request $request, ShopItem $shopItem)
    {
        $shopItem->price = (float) $request->price;
        $shopItem->discount_price = (float) $request->discount_price;
        $shopItem->save();

        $request->session()->flash('message', 'Товар успешно обновлен!');

        return redirect(route('shop.show', $shopItem->shop_id));
    }

    public function priceDownload(Shop $shop)
    {
        $path = storage_path('prices/' . $shop->id);

        $files = glob($path . '/*.xlsx');

        if (!isset($files[0])) {
            abort(404);
        }

        return response()->download($files[0]);
    }

}
