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

class GeneratePrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $priceName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($priceName)
    {
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
        $sheet->setCellValue('C1', 'Наименование');
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->setCellValue('D1', 'Мин.цена');
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->setCellValue('E1', 'Цена');
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->setCellValue('F1', 'Остаток');
        $sheet->getColumnDimension('F')->setWidth(14);

        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getStyle('1:1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D:F')->getAlignment()->setHorizontal('center');

        foreach (Item::all() as $key => $item) {
            $row = $key + 2;

            $sheet->setCellValue('A' . $row, $item->article);
            $sheet->setCellValue('B' . $row, $item->brand->name);
            $sheet->setCellValue('C' . $row, $item->name);
            $sheet->setCellValue(
                'D' . $row,
                $item->contractorItems()->orderBy('price')->first()
                    ? $item->contractorItems()->orderBy('price')->first()->price
                    : ''
            );
            $sheet->setCellValue('E' . $row, $item->price);
            $sheet->setCellValue('F' . $row, $item->stock);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($this->priceName);
    }
}
