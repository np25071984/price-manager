<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Item;
use App\Brand;
use App\Relation;
use App\ContractorItem;
use App\Contractor;

class BindItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:bind {contractor_id : The ID of contractor which items will be binded} {file : File name with relations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind items with contractor\'s ones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contractorId = (int) $this->argument('contractor_id');
        $file = $this->argument('file');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

        $contractor = Contractor::findOrFail($contractorId);

        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $name = trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
            if ($name === '<не указано') {
                continue;
            }

            $item = Item::where(['name' => $name])->first();
            if (!$item) {
                $this->line(sprintf("В прайсе не найден товар с названием '%s'", $name));
                continue;
            }

            $contractorName = trim($worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue());

            $contractorItem = $contractor->items()->where(['name' => $contractorName])->first();
            if ($contractorItem) {
                $relation = Relation::where([
                    'item_id' => $item->id,
                    'contractor_id' => $contractor->id,
                ])->exists();
                if ($relation) {
                    $this->line(sprintf("Для товара '%s' уже найдена связь товаром поставщика '%s'", $item->name, $contractor->name));
                } else {
                    Relation::firstOrCreate([
                        'item_id' => $item->id,
                        'contractor_id' => $contractor->id,
                        'contractor_item_id' => $contractorItem->id,
                    ]);
                }
            } else {
                $this->line(sprintf("Для поставщика '%s' не найден товар с названием '%s'", $contractor->name, $name));
            }

        }

    }
}
