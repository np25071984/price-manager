<?php

namespace App;

use App\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractorItem extends Model
{
//    use SoftDeletes;

    protected $fillable = ['contractor_id', 'article', 'name', 'price'];

    protected static function boot()
    {
        parent::boot();

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

    public function scopeUnrelated($query, $colName) {
        $contractorItemsWithoutRelation = \DB::table('contractor_items')
            ->select('contractor_items.id')
            ->leftJoin('relations', 'contractor_items.id', '=', 'relations.contractor_item_id')
            ->whereNull('relations.id');

        return $query->wherein("{$colName}.id", $contractorItemsWithoutRelation);
    }

    public static function smartSearch($searchString)
    {
        if (trim($searchString) === '') {
            $items = ContractorItem::from('contractor_items as search_result');
        } elseif (is_numeric($searchString)) {
            /** reckon query string is an item article */
            $items = ContractorItem::from('contractor_items as search_result')->where(['article' => $searchString]);
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

            $tsvItems = ContractorItem::query()->select([
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
            $trgItems = ContractorItem::query()
                ->select(['*', \DB::raw("similarity(name, ?) as rank")])
                ->setBindings([$searchStringOrig]);
            $trgItems->whereRaw('name % ?', [$searchStringOrig]);

            $items = $tsvItems->union($trgItems)
                ->orderBy('rank', 'desc');

            $items = ContractorItem::from(\DB::raw("({$items->toSql()}) as search_result"))
                ->mergeBindings($items->getQuery());
        }

        return $items;
    }
}
