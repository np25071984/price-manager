<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SmartSearch;

class ContractorItem extends SmartSearch
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'contractor_id', 'article', 'name', 'price'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new UserScope);

        static::deleting(function (ContractorItem $contractorItem) {
            Relation::where(['contractor_item_id' => $contractorItem->id])->delete();
        });
    }

    public function relatedItem()
    {
        return $this->hasOneThrough('App\Item', 'App\Relation', 'contractor_item_id', 'id', 'id', 'item_id');
    }

    public function contractor()
    {
        return $this->hasOne('App\Contractor', 'id', 'contractor_id');
    }

    public function scopeUnrelated($query) {
        $contractorItemsWithoutRelation = \DB::table('contractor_items')
            ->select('contractor_items.id')
            ->leftJoin('relations', 'contractor_items.id', '=', 'relations.contractor_item_id')
            ->whereNull('relations.id');

        return $query->wherein($this->qualifyColumn("id"), $contractorItemsWithoutRelation);
    }

    public static function smartSearch($searchString, $contractorId = null)
    {
        if (trim($searchString) === '') {
            $items = ContractorItem::query();
            if ($contractorId) {
                $items->where(['contractor_items.contractor_id' => $contractorId]);
            }
        } elseif (is_numeric($searchString)) {
            /** reckon query string is an item article */
            $items = ContractorItem::where(['contractor_items.article' => $searchString]);
            if ($contractorId) {
                $items->where(
                    ['contractor_items.contractor_id' => $contractorId]
                );
            }
        } else {
            $tsvItems = self::getTsvQuery($searchString);
            if ($contractorId) {
                $tsvItems->where([$tsvItems->qualifyColumn('contractor_id') => $contractorId]);
            }

            $trgmItems = self::getTrgmQuery($searchString);
            if ($trgmItems && $contractorId) {
                $trgmItems->where([$tsvItems->qualifyColumn('contractor_id') => $contractorId]);
            }

            $items = self::getSmartSearchQuery($tsvItems, $trgmItems, 'contractor_items');
        }

        return $items;
    }


    public static function getTableName()
    {
        return (new self())->getTable();
    }

}
