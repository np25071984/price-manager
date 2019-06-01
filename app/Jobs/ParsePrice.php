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

        if ($this->contractorId) {
            $contractor = Contractor::findOrFail($this->contractorId);
            $columnName = $contractor->config['col_name'];
            $columnPrice = $contractor->config['col_price'];
        } else {
            $contractor = null;
            $columnName = 3;
            $columnPrice = 5;
        }

        \DB::beginTransaction();
        for ($row = 0; $row <= $highestRow; $row++) {
            if ($worksheet->getCellByColumnAndRow($columnName, $row)->isInMergeRange()
                || $worksheet->getCellByColumnAndRow($columnPrice, $row)->isInMergeRange()) {
                continue;
            }

            var_dump($contractor->config);
            $name = trim($worksheet->getCellByColumnAndRow($columnName, $row)->getCalculatedValue());
            if ($name === '') {
                continue;
            }

            $price = floatval(
                str_replace(
                    ',',
                    '.',
                    trim($worksheet->getCellByColumnAndRow($columnPrice, $row)->getCalculatedValue())
                )
            );
            if ($price === floatval(0)) {
                continue;
            }

            try {

                if ($contractor) {
                    $contractorItem = $contractor->items()->where(['name' => $name])->first();

                    if ($contractorItem) {
                        $contractorItem->name = $name;
                        $contractorItem->price = $price;
                        $contractorItem->save();
                    } else {
                        $contractorItem = ContractorItem::create([
                            'contractor_id' => $contractor->id,
                            'name' => $name,
                            'price' => $price,
                        ]);
                    }

                    $item = Item::where(['name' => $name])->first();
                    if ($item) {
                        Relation::firstOrCreate([
                            'item_id' => $item->id,
                            'contractor_item_id' => $contractor->id,
                        ]);
                    }
                } else {
                    $brandName = trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
                    $brand = Brand::where('name', $brandName)->first();
                    if (!$brand) {
                        $brand = Brand::create([
                            'name' => $brandName
                        ]);
                    }

                    $article = trim($worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue());
                    $item = Item::where('article', $article)->first();

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

            } catch(\Exception $e) {
                \DB::rollback();
                throw $e;
            }
        }
        \DB::commit();

    }

    public function getContractorId() {
        return $this->contractorId;
    }

}
