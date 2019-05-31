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

class ParsePrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const UPLOADED = 1;
    const IN_PROCESS = 2;
    const ERROR = 3;

    protected $contractorId;
    protected $price;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contractorId, $price)
    {
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
        /** Create a new Xls Reader  **/
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

        $spreadsheet = $reader->load($this->price);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();


        if ($this->contractorId == 1) {
            $startRow = 11;
        } else {
            $startRow = 2;
        }

        for ($row = $startRow; $row <= $highestRow; $row++) {
            if ($worksheet->getCellByColumnAndRow(2, $row)->isInMergeRange()) {
                continue;
            }

            try {
                \DB::beginTransaction();

                switch ($this->contractorId) {
                    case 1:
                        $name = trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
                        $price = floatval(
                            str_replace(
                                ',',
                                '.',
                                trim($worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue())
                            )
                        );

                        $contractorItem = ContractorItem::where([
                            'contractor_id' => $this->contractorId,
                            'name' => $name,
                        ])->first();

                        if ($contractorItem) {
                            $contractorItem->contractor_id = $this->contractorId;
                            $contractorItem->name = $name;
                            $contractorItem->price = $price;
                            $contractorItem->save();
                        } else {
                            $contractorItem = ContractorItem::create([
                                'contractor_id' => $this->contractorId,
                                'name' => $name,
                                'price' => $price,
                            ]);
                        }

                        $item = Item::where(['name' => $name])->first();
                        if ($item) {
                            Relation::firstOrCreate([
                                'item_id' => $item->id,
                                'contractor_item_id' => $contractorItem->id,
                            ]);
                        }
                        break;
                    default:
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
                        $price = floatval(
                            str_replace(
                                ',',
                                '.',
                                trim($worksheet->getCellByColumnAndRow(5, $row)->getCalculatedValue())
                            )
                        );
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
                }

                \DB::commit();
            } catch(PDOException $e) {
                \DB::rollback();
                throw $e;
            }
        }

    }

    public function getContractorId() {
        return $this->contractorId;
    }

}
