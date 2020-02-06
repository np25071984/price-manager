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
        $sheet->getColumnDimension('E')->setWidth(20);

        $sheet->setCellValue('F1', 'Имя атрибута 1');
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->setCellValue('G1', 'Значение(-я) аттрибута(-ов) 1');
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->setCellValue('H1', 'Видимость атрибута 1');
        $sheet->getColumnDimension('H')->setWidth(14);
        $sheet->setCellValue('I1', 'Глобальный атрибут 1');
        $sheet->getColumnDimension('I')->setWidth(14);

        $sheet->setCellValue('J1', 'Имя атрибута 2');
        $sheet->getColumnDimension('J')->setWidth(18);
        $sheet->setCellValue('K1', 'Значение(-я) аттрибута(-ов) 2');
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->setCellValue('L1', 'Видимость атрибута 2');
        $sheet->getColumnDimension('L')->setWidth(14);
        $sheet->setCellValue('M1', 'Глобальный атрибут 2');
        $sheet->getColumnDimension('M')->setWidth(14);

        $sheet->setCellValue('N1', 'Имя атрибута 3');
        $sheet->getColumnDimension('N')->setWidth(18);
        $sheet->setCellValue('O1', 'Значение(-я) аттрибута(-ов) 3');
        $sheet->getColumnDimension('O')->setWidth(25);
        $sheet->setCellValue('P1', 'Видимость атрибута 3');
        $sheet->getColumnDimension('P')->setWidth(14);
        $sheet->setCellValue('Q1', 'Глобальный атрибут 3');
        $sheet->getColumnDimension('Q')->setWidth(14);

        $sheet->setCellValue('R1', 'Имя атрибута 4');
        $sheet->getColumnDimension('R')->setWidth(18);
        $sheet->setCellValue('S1', 'Значение(-я) аттрибута(-ов) 4');
        $sheet->getColumnDimension('S')->setWidth(25);
        $sheet->setCellValue('T1', 'Видимость атрибута 4');
        $sheet->getColumnDimension('T')->setWidth(14);
        $sheet->setCellValue('U1', 'Глобальный атрибут 4');
        $sheet->getColumnDimension('U')->setWidth(14);

        $sheet->setCellValue('V1', 'Имя атрибута 5');
        $sheet->getColumnDimension('V')->setWidth(18);
        $sheet->setCellValue('W1', 'Значение(-я) аттрибута(-ов) 5');
        $sheet->getColumnDimension('W')->setWidth(25);
        $sheet->setCellValue('X1', 'Видимость атрибута 5');
        $sheet->getColumnDimension('X')->setWidth(14);
        $sheet->setCellValue('Y1', 'Глобальный атрибут 5');
        $sheet->getColumnDimension('Y')->setWidth(14);

        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getStyle('1:1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D:G')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G:G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE);
        $sheet->getStyle('J:J')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('N:N')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('R:R')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('V:V')->getAlignment()->setHorizontal('center');

        $items = Item::whereIn('id', ShopItem::query()->select(['item_id'])->where(['shop_id' => $this->shopId]))->get();

        foreach ($items as $key => $item) {
            $row = $key + 2;

            $sheet->setCellValue('A' . $row, $item->article);

            $brand = $item->brand()->first();
            $sheet->setCellValue('B' . $row, $brand->name);

            $sheet->setCellValue('C' . $row, $item->name);

            $shopItem = ShopItem::firstOrCreate([
                'shop_id' => $this->shopId,
                'item_id' => $item->id,
            ]);
            $price = $shopItem->price;

            $aromas = [];
            foreach ($item->aromas() as $aroma) {
                $aromas[] = $aroma->name;
            }

            $sheet->setCellValue('D' . $row, $price);

            $sheet->setCellValue('E' . $row, $item->stock);

            $sheet->setCellValue('F' . $row, 'Объем');
            $sheet->setCellValue('G' . $row, $item->value);
            $sheet->setCellValue('H' . $row, 1);
            $sheet->setCellValue('I' . $row, 1);

            $sheet->setCellValue('J' . $row, 'Год выпуска');
            $sheet->setCellValue('K' . $row, $item->year);
            $sheet->setCellValue('L' . $row, 1);
            $sheet->setCellValue('M' . $row, 1);

            $sheet->setCellValue('N' . $row, 'Страна');
            $sheet->setCellValue('O' . $row, $item->country ? $item->country->name : '');
            $sheet->setCellValue('P' . $row, 1);
            $sheet->setCellValue('Q' . $row, 1);

            $sheet->setCellValue('R' . $row, 'Ароматы');
            $sheet->setCellValue('S' . $row, join(',', $aromas));
            $sheet->setCellValue('T' . $row, 1);
            $sheet->setCellValue('U' . $row, 1);

            $sheet->setCellValue('V' . $row, 'Вид');
            $sheet->setCellValue('W' . $row, $item->type);
            $sheet->setCellValue('X' . $row, 1);
            $sheet->setCellValue('Y' . $row, 1);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($this->priceName);
    }

    public function getShopId() {
        return $this->shopId;
    }
}
