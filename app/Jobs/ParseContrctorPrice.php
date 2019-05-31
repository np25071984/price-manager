<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Item;
use App\Relation;
use App\ContractorItem;

class ParseContrctorPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
