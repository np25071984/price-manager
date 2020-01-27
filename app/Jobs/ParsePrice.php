<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Item;
use App\Brand;
use App\Relation;
use App\ContractorItem;
use App\Contractor;
use App\Aroma;
use App\ItemAroma;
use App\Country;
use App\Group;

class ParsePrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const UPLOADED = 1;
    const IN_PROCESS = 2;
    const ERROR = 3;

    protected $userId;
    protected $contractorId;
    protected $price;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $contractorId, $price)
    {
        $this->userId = $userId;
        $this->contractorId = $contractorId;
        $this->price = $price;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->price);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        \DB::beginTransaction();
        if ($this->contractorId) {
            $contractor = Contractor::query()
                ->where([
                    'id' => $this->contractorId,
                ])
                ->firstOrFail();

            $columnArticle = isset($contractor->config['col_article']) ? $contractor->config['col_article'] : null;
            $columnName = $contractor->config['col_name'];
            $columnPrice = $contractor->config['col_price'];
            $startRow = 1;

            // Truly delete all SoftDeleted items (bunch deleting doesn't support the Events!)
            $contractor->items()->onlyTrashed()->forceDelete();

            // SoftDelete all remain contractor's items
            $contractor->items()->delete();

            for ($row = $startRow; $row <= $highestRow; $row++) {
                $name = trim($worksheet->getCellByColumnAndRow($columnName, $row)->getCalculatedValue());
                if ($name === '') {
                    continue;
                }

                if ($columnArticle) {
                    $article = trim($worksheet->getCellByColumnAndRow($columnArticle, $row)->getCalculatedValue());
                } else {
                    $article = md5($name);
                }
                if ($article === '') {
                    continue;
                }

                $price = floatval(
                    str_replace(
                        ',',
                        '.',
                        trim($worksheet->getCellByColumnAndRow($columnPrice, $row)->getCalculatedValue())
                    )
                );

                try {
                    if ($contractor) {
                        if ($price === floatval(0)) {
                            continue;
                        }

                        $contractorItem = $contractor
                            ->items()
                            ->withTrashed()
                            ->where([
                                'article' => $article
                            ])->first();

                        if ($contractorItem) {
                            $contractorItem->restore();
                            $contractorItem->name = $name;
                            $contractorItem->price = $price;
                            $contractorItem->save();
                        } else {
                            $contractorItem = ContractorItem::query()
                                ->create([
                                    'contractor_id' => $contractor->id,
                                    'article' => $article,
                                    'name' => $name,
                                    'price' => $price,
                                ]);
                        }

                        $item = Item::query()
                            ->where([
                                'name' => $name,
                            ])->first();
                        if ($item) {
                            Relation::firstOrCreate([
                                'item_id' => $item->id,
                                'contractor_id' => $contractor->id,
                                'contractor_item_id' => $contractorItem->id,
                            ]);
                        }
                    } else {
                        $brandName = trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
                        $brand = Brand::query()
                            ->firstOrCreate([
                                'name' => $brandName,
                            ]);

                        $article = trim($worksheet->getCellByColumnAndRow($columnArticle, $row)->getCalculatedValue());
                        $item = Item::query()
                            ->where([
                                'article' => $article,
                            ])->first();

                        $stock = intval(trim($worksheet->getCellByColumnAndRow(8, $row)->getCalculatedValue()));

                        $duplicateItem = Item::query()
                            ->where([
                                'name' => $name
                            ])
                            ->where('article', '!=', $article)
                            ->first();
                        if ($duplicateItem) {
                            throw new \Exception(sprintf("Товар с имененм '%s' уже существует в прайсе!", $name));
                        }

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
                    }
                } catch(\Exception $e) {
                    \DB::rollback();
                    throw $e;
                }
            }
        } else {
            $columnType = 2;
            $columnArticle = 3;
            $columnName = 4;
            $columnDesctiption = 9;
            $columnBrand = 20;
            $attrs = [
                21 => 22,
                25 => 26,
                29 => 30,
                33 => 34,
                37 => 38,
            ];
            $startRow = 2;

            $group = null;
            for ($row = $startRow; $row <= $highestRow; $row++) {
                $itemType = trim($worksheet->getCellByColumnAndRow($columnType, $row)->getValue());
                $article = trim($worksheet->getCellByColumnAndRow($columnArticle, $row)->getValue());
                $name = trim($worksheet->getCellByColumnAndRow($columnName, $row)->getValue());

                $aromas = [];
                $country = null;
                $year = null;
                $volume = null;
                $type = 'Dry perfume';

                try {
                    $brand = Brand::firstOrCreate([
                        'name' => trim($worksheet->getCellByColumnAndRow($columnBrand, $row)->getValue())
                    ]);

                    foreach ($attrs as $colName => $colVal) {
                        $attrName = trim($worksheet->getCellByColumnAndRow($colName, $row)->getValue());
                        $attrVal = trim($worksheet->getCellByColumnAndRow($colVal, $row)->getValue());

                        switch ($attrName) {
                            case 'Объем':
                                $volume = $attrVal;
                                break;
                            case 'Год выпуска':
                                $year = $attrVal;
                                break;
                            case 'Ароматы':
                                foreach (explode(',', $attrVal) as $val) {
                                    $aromas[] = Aroma::firstOrCreate([
                                        'name' => trim($val)
                                    ]);
                                }
                                break;
                            case 'Страна':
                                $country = Country::firstOrCreate([
                                    'name' => $attrVal
                                ]);
                                break;
                            case 'Вид':
                                switch ($attrVal) {
                                    case 'Духи':
                                        $type = 'Perfume';
                                        break;
                                    case 'Туалетная вода':
                                        $type = 'EDT';
                                        break;
                                    case 'Экстракт духов':
                                        $type = 'Perfume extract';
                                        break;
                                    case 'Парфюмированная вода':
                                        $type = 'EDP';
                                        break;
                                }
                        }
                    }

                    switch ($itemType) {
                        case 'simple':
                            $group = null;
                        case 'variation':
                            $item = Item::firstOrNew([
                                'article' => $article
                            ]);
                            $item->name = $name;
                            $item->brand_id = $brand ? $brand->id : null;
                            $item->country_id = $country ? $country->id : null;
                            // $item->year = $year;
                            $item->type = $type;
                            $item->volume = (int)$volume;
                            $item->group_id = $group ? $group->id : null;
                            $item->stock = 0;
                            $item->save();
                            ItemAroma::query()->where([
                                'item_id' => $item->id,
                            ])->delete();
                            foreach($aromas as $aroma) {
                                ItemAroma::create([
                                    'item_id' => $item->id,
                                    'aroma_id' => $aroma->id,
                                ]);
                            }
                            break;
                        case 'variable':
                            $group = Group::firstOrCreate([
                                'name' => $this->clearName($name),
                            ]);
                            break;
                    }
                } catch(\Exception $e) {
                    \DB::rollback();
                    throw $e;
                }

            }
        }

        \DB::commit();
    }

    private function clearName($name) {
        $name = str_replace(['EDP', 'EDT'], '', $name);
        $name = str_replace(' - ', '', $name);
        $name = preg_replace('/\d+ml\s?(tester|test)?/', '', $name);
        $name = preg_replace('/\s{2,}/', ' ', $name);
        return trim($name);
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getContractorId() {
        return $this->contractorId;
    }

}
