<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Filesystem;

use App\Brand;
use App\Country;
use App\Shop;
use App\ShopItem;
use App\Group;
use App\Item;
use App\Aroma;
use App\ItemAroma;
use App\Tag;
use App\ItemTag;
use App\Jobs\ParsePrice;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\PriceProcessingJobStatus;

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
        $aromas = Aroma::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();
        return view(
            'item/create',
            compact('brands', 'countries', 'groups', 'shops', 'types', 'aromas', 'tags')
        );
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
                'description' => $request->description,
                'volume' => $request->volume,
                'year' => $request->year,
                'stock' => $request->stock,
                'is_tester' => $request->is_tester,
            ]);

            if ($request->shop_id) {
                foreach ($request->shop_id as $shopId) {
                    ShopItem::create([
                        'item_id' => $item->id,
                        'shop_id' => $shopId,
                    ]);
                }
            }

            if ($request->aroma_id) {
                foreach ($request->aroma_id as $aromaId) {
                    ItemAroma::create([
                        'item_id' => $item->id,
                        'aroma_id' => $aromaId,
                    ]);
                }
            }

            $this->fillTags($item, $request->tags);

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
        $aromas = Aroma::orderBy('name', 'asc')->get();
        $allTags = Tag::orderBy('name', 'asc')->get();
        $itemTags = $item->tags;

        return view(
            'item/edit',
            compact('item', 'shops', 'brands', 'countries', 'groups', 'types', 'aromas', 'allTags', 'itemTags')
        );
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
            $item->description = $request->description;
            $item->volume = $request->volume;
            $item->year = $request->year;
            $item->stock = $request->stock;
            $item->is_tester = $request->is_tester ? true : false;

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

            ItemAroma::query()->where([
                'item_id' => $item->id,
            ])->delete();
            if ($request->aroma_id) {
                foreach ($request->aroma_id as $aromaId) {
                    ItemAroma::create([
                        'item_id' => $item->id,
                        'aroma_id' => $aromaId,
                    ]);
                }
            }

            ItemTag::query()->where([
                'item_id' => $item->id,
            ])->delete();
            $this->fillTags($item, $request->tags);

            return $item;
        });

        $item->save();

        $request->session()->flash('message', 'Товар успешно обновлен!');

        return redirect(route('item.show' , $item->id));
    }

    private function fillTags(Item $item, $requestTags)
    {
        if ($requestTags) {
            foreach(json_decode($requestTags) as $tag) {
                if (empty($tag->key)) {
                    // a new tag
                    $tt = Tag::firstOrCreate(['name' => $tag->value]);
                    $t = $tt->id;
                } else {
                    // an existed tag
                    $t = $tag->key;
                }

                ItemTag::create([
                    'item_id' => $item->id,
                    'tag_id' => $t,
                ]);
            }
        }

        // remove unused tags
        Tag::query()->whereNotIn('id', ItemTag::query()->select('tag_id')->distinct())->delete();
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
