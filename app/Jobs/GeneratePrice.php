<?php

namespace App\Jobs;

use App\Scopes\UserScope;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Item;
use App\Brand;

class GeneratePrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $priceName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $priceName)
    {
        $this->userId = $userId;
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
        $sheet->setCellValue('D1', 'Оптовая (закупочная) цена');
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->setCellValue('E1', 'Розничная цена');
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->setCellValue('F1', 'Доступное количество');
        $sheet->getColumnDimension('F')->setWidth(14);
        $sheet->setCellValue('G1', 'Наценка');
        $sheet->getColumnDimension('G')->setWidth(14);

        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getStyle('1:1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D:G')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G:G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE);

        $items = Item::withoutGlobalScope(UserScope::class)
            ->where([
                'user_id' => $this->userId,
            ])
            ->get();

        foreach ($items as $key => $item) {
            $row = $key + 2;

            $sheet->setCellValue('A' . $row, $item->article);

            $brand = $item->brand()->withoutGlobalScope(UserScope::class)->first();
            $sheet->setCellValue('B' . $row, $brand->name);

            $sheet->setCellValue('C' . $row, $item->name);

            if ($item->stock === '0') {
                $bestPriceContractorItem = $item
                    ->contractorItems()
                    ->withoutGlobalScope(UserScope::class)
                    ->orderBy('price')
                    ->first();

                $opt = $bestPriceContractorItem->price;
            } else {
                $opt = $item->price;
            }
            $sheet->setCellValue('D' . $row, $opt);

            switch (true) {
                case $opt < 51:
                    $markup = 45;
                    break;
                case $opt < 101:
                    $markup = 40;
                    break;
                case $opt < 151:
                    $markup = 35;
                    break;
                case $opt < 201:
                    $markup = 30;
                    break;
                case $opt < 251:
                    $markup = 25;
                    break;
                case $opt < 301:
                    $markup = 20;
                    break;
                case $opt < 401:
                    $markup = 15;
                    break;
                case $opt < 600:
                    $markup = 10;
                    break;
                case $opt < 700:
                    $markup = 8;
                    break;
                case $opt < 1000:
                    $markup = 7;
                    break;
                default:
                    $markup = 6;
                    break;
            }

            $sheet->setCellValue('E' . $row, "=SUM(D{$row}, D{$row} * G{$row})");

            $sheet->setCellValue('F' . $row, $item->stock);

            $sheet->setCellValue('G' . $row, $markup / 100);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($this->priceName);
    }
}
