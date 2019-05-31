<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Brand;
use App\Item;
use Illuminate\Support\Facades\File;

class ParseItemPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $price;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($price)
    {
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

                \DB::commit();
            } catch(PDOException $e) {
                \DB::rollback();
                throw $e;
            }
        }

        File::delete($this->price);
    }

    public function getContractorId() {
        return null;
    }
}
