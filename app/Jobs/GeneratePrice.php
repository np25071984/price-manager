<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Item;
use App\ShopItem;

class GeneratePrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const UPLOADED = 1;
    const IN_PROCESS = 2;
    const ERROR = 3;

    protected $shopId;
    protected $priceName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId, $priceName)
    {
        $this->shopId = $shopId;
        $this->priceName = $priceName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Артикул');
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->setCellValue('B1', 'Бренд');
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->setCellValue('C1', 'Наименование товара');
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->setCellValue('D1', 'Цена');
        $sheet->setCellValue('E1', 'Доступное количество');
        $sheet->getColumnDimension('E')->setWidth(14);

        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getStyle('1:1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D:G')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G:G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE);

        $items = Item::whereIn('id', ShopItem::query()->select(['item_id'])->where(['shop_id' => $this->shopId]))->get();

        foreach ($items as $key => $item) {
            $row = $key + 2;

            $sheet->setCellValue('A' . $row, $item->article);

            $brand = $item->brand()->first();
            $sheet->setCellValue('B' . $row, $brand->name);

            $sheet->setCellValue('C' . $row, $item->name);

            if ($item->stock === '0') {
                $bestPriceContractorItem = $item
                    ->contractorItems()
                    ->orderBy('price')
                    ->first();

                $price = $bestPriceContractorItem->price;
            } else {
                $shopItem = ShopItem::firstOrCreate([
                    'shop_id' => $this->shopId,
                    'item_id' => $item->id,
                ]);

                $price = $shopItem->price;
            }
            $sheet->setCellValue('D' . $row, $price);

            $sheet->setCellValue('E' . $row, $item->stock);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($this->priceName);
    }

    public function getShopId() {
        return $this->shopId;
    }
}
