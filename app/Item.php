<?php

namespace App;

use App\Relation;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['brand_id', 'article', 'name', 'price', 'stock'];

    protected static function boot()
    {
        parent::boot();

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

    public function scopeUnrelated($query, $colName) {
        $itemsWithoutRelation = \DB::table('items')
            ->select('items.id')
            ->leftJoin('relations', 'items.id', '=', 'relations.item_id')
            ->whereNull('relations.id');

        return $query->wherein("{$colName}.id", $itemsWithoutRelation);
    }

    public static function smartSearch($searchString)
    {
        if (trim($searchString) === '') {
            $items = Item::from('items as search_result');
        } elseif (is_numeric($searchString)) {
            /** reckon query string is an item article */
            $items = Item::from('items as search_result')->where(['article' => $searchString]);
        } else {
            $searchString = preg_replace('/\W/u', ' ', $searchString);
            $searchStringOrig = trim(preg_replace('/\s+/u', ' ', $searchString));

            /** extract digits from the query */
            preg_match_all('/\d+/', $searchStringOrig, $matches);
            $numbers = $matches[0];
            $searchString = trim(preg_replace('/\d+/', '', $searchStringOrig));

            /** extract russian words */
            preg_match_all('/[А-Яа-яЁё]+/u', $searchString, $matches);
            $russian = $matches[0];
            $searchString = trim(preg_replace('/[А-Яа-яЁё]+/u', '', $searchString));

            $russianQuery = null;
            if (count($russian)) {
                foreach ($russian as $key => $word) {
                    $russian[$key] = $word . ':*';
                }
                $russianQuery = implode(' & ', $russian);
                unset($russian);
            }

            /** extract english words */
            $english = preg_split('/\s+/', $searchString, -1, PREG_SPLIT_NO_EMPTY);

            $englishQuery = null;
            if (count($english)) {
                foreach ($english as $key => $word) {
                    $english[$key] = $word . ':*';
                }
                $englishQuery = implode(' & ', $english);
                unset($english);
            }

            $tsvItems = Item::query()->select([
                    '*',
                    \DB::raw("(ts_rank(tsvector_token, "
                        . "ts_rewrite(to_tsquery('english', coalesce(?, '')), 'SELECT t, s FROM aliases')) "
                        . "+ ts_rank(tsvector_token, "
                        . "ts_rewrite(to_tsquery('russian', coalesce(?, '')), 'SELECT t, s FROM aliases'))"
                        . ") as rank")]
            )->setBindings([$englishQuery, $russianQuery]);
            foreach ($numbers as $number) {
                $tsvItems->where('name', 'like', "%{$number}%");
            }

            if ($russianQuery) {
                $tsvItems->whereRaw(
                    "tsvector_token @@ ts_rewrite(to_tsquery('russian', ?), 'SELECT t, s FROM aliases')",
                    [$russianQuery]
                );
            }
            if ($englishQuery) {
                $tsvItems->whereRaw(
                    "tsvector_token @@ ts_rewrite(to_tsquery('english', ?), 'SELECT t, s FROM aliases')",
                    [$englishQuery]
                );
            }

            // pg_trgm make no sense in current case due to short search query and too long data string in DB
            $trgItems = Item::query()
                ->select(['*', \DB::raw("similarity(name, ?) as rank")])
                ->setBindings([$searchStringOrig]);
            $trgItems->whereRaw('name % ?', [$searchStringOrig]);

            $items = $tsvItems->union($trgItems)
                ->orderBy('rank', 'desc');

            $items = Item::from(\DB::raw("({$items->toSql()}) as search_result"))->mergeBindings($items->getQuery());
        }

        return $items;
    }

}
