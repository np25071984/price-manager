<?php

namespace App\Console\Commands;

use App\Scopes\UserScope;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Item;
use App\Brand;
use App\Relation;
use App\ContractorItem;
use App\Contractor;
use App\User;

class BindItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:bind '
        . '{user_id : The ID of user} '
        . '{contractor_id : The ID of contractor which items will be binded} '
        . '{file : File name with relations}';

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
        $userId = (int) $this->argument('user_id');
        $contractorId = (int) $this->argument('contractor_id');
        $file = $this->argument('file');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

        $user = User::find($userId);
        if (!$user) {
            $this->error(sprintf("user_id = %s didn't find!", $userId));
            return;
        }

        $contractor = Contractor::withoutGlobalScope(UserScope::class)
            ->where([
                'user_id' => $userId,
                'id' => $contractorId,
            ])
            ->first();

        if (!$contractor) {
            $this->error(sprintf("contractor_id = %s didn't find!", $contractorId));
            return;
        }

        if (!file_exists($file)) {
            $this->error(sprintf("File %s doesn't exists!", $file));
            return;
        }

        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $name = trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
            if ($name === '<не указано') {
                continue;
            }

            $item = Item::withoutGlobalScope(UserScope::class)
                ->where([
                    'user_id' => $userId,
                    'name' => $name,
                ])->first();
            if (!$item) {
                $this->line(sprintf("В прайсе не найден товар с названием '%s'", $name));
                continue;
            }

            $contractorName = trim($worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue());

            $contractorItem = $contractor
                ->withoutGlobalScope(UserScope::class)
                ->items()
                ->where([
                    'user_id' => $userId,
                    'name' => $contractorName,
                ])->first();
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
                $this->line(sprintf("Для поставщика '%s' не найден товар с названием '%s'", $contractor->name, $contractorName));
            }

        }

    }
}
