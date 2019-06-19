<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use App\SmartSearch;

class Item extends SmartSearch
{
    protected $fillable = ['user_id', 'brand_id', 'article', 'name', 'price', 'stock'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new UserScope);

        static::deleting(function (Item $item) {
            Relation::where(['item_id' => $item->id])->delete();
        });
    }

    /**
     * @return App\Brand
     */
    public function brand() {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }

    public function contractorItems()
    {
        return $this->belongsToMany('App\ContractorItem', 'relations');
    }

    public function scopeUnrelated($query, $contractorId) {
        $contractorRelations =\DB::table('relations')->select('item_id')->where(['contractor_id' => $contractorId]);

        $itemsWithoutRelation = \DB::table('items')
            ->select('items.id')
            ->leftJoin('relations', 'items.id', '=', 'relations.item_id')
            ->where(function ($query) use ($contractorRelations) {
                $query->whereNotIn('items.id', $contractorRelations)
                    ->orWhereNull('relations.id');
            });

        return $query->wherein($this->qualifyColumn("id"), $itemsWithoutRelation);
    }

    public static function smartSearch($searchString)
    {
        if (trim($searchString) === '') {
            $items = self::query();
        } elseif (is_numeric($searchString)) {
            /** reckon query string is an item article */
            $items = self::where(['items.article' => $searchString]);
        } else {
            $tsvItems = self::getTsvQuery($searchString);

            $trgmItems = self::getTrgmQuery($searchString);

            $items = self::getSmartSearchQuery($tsvItems, $trgmItems, 'items');
        }

        return $items;
    }

    public static function getTableName()
    {
        return (new self())->getTable();
    }

}
