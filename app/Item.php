<?php

namespace App;

use App\SmartSearch;

class Item extends SmartSearch
{
    protected $fillable = ['brand_id', 'group_id', 'country_id', 'article', 'name', 'type', 'volume', 'stock'];

    public static function getTypes()
    {
        return ['EDP', 'EDT', 'EDC', 'Perfume', 'Perfume extract', 'Dry perfume', 'Perfume oil'];
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Item $item) {
            Relation::where(['item_id' => $item->id])->delete();
        });
    }

    /**
     * Get all of the shops where the item present.
     * @return App\Shop[]
     */
    public function shops()
    {
        return $this->hasManyThrough(
                'App\Shop',
                'App\ShopItem',
                'item_id',
                'id',
                'id',
                'shop_id'
            )->orderBy('name', 'asc');
    }

    /**
     * Get all shop`s Ids, where the item present.
     *
     * @return array
     */
    public function shopIds()
    {
        $ids = [];
        foreach ($this->shops as $shop) {
            $ids[] = $shop->id;
        }
        return $ids;
    }

    /**
     * @return App\Brand
     */
    public function brand() {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }

    /**
     * @return App\Country
     */
    public function country() {
        return $this->hasOne('App\Country', 'id', 'country_id');
    }

    /**
     * @return App\Group
     */
    public function group() {
        return $this->hasOne('App\Group', 'id', 'group_id');
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
