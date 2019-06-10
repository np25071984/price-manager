<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
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

    public function scopeUnrelated($query) {
        $itemsWithoutRelation = \DB::table('items')
            ->select('items.id')
            ->leftJoin('relations', 'items.id', '=', 'relations.item_id')
            ->whereNull('relations.id');

        return $query->wherein($this->qualifyColumn("id"), $itemsWithoutRelation);
    }

    public static function smartSearch($searchString)
    {
        if (trim($searchString) === '') {
            $items = Item::query();
        } elseif (is_numeric($searchString)) {
            /** reckon query string is an item article */
            $items = Item::where(['items.article' => $searchString]);
        } else {
            $searchStringOrig = $searchString;
            /** replace special chars by space char */
            $searchString = preg_replace('/[()&:]/u', ' ', $searchString);

            $searchString = preg_replace('/!+/u', '!', $searchString);
            $searchString = trim(preg_replace('/![^\w]/u', ' ', $searchString), ' !');

            /** replace all '!' which is not on the first position of a word */
            $searchString = preg_replace('/(?<=\w)(!+)/u', ' ', $searchString);

            $searchString = trim(preg_replace('/\s+/u', ' ', $searchString));

            /** extract russian words */
            preg_match_all('/!?[А-Яа-яЁё]+/u', $searchString, $matches);
            $russian = $matches[0];
            $searchString = trim(preg_replace('/!?[А-Яа-яЁё]+/u', '', $searchString));

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
                    . ") as rank")
            ])->setBindings([$englishQuery, $russianQuery], 'select');

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

            /** pg_trgm make no sense with short search queries */
            if (mb_strlen($searchStringOrig) > 1) {
                preg_match_all('/\s!\w+/u', $searchStringOrig, $matches);
                $minuses = (isset($matches[0]) && (count($matches[0]) > 0)) ? $matches[0] : null;
                $searchString = trim(preg_replace('/\s!\w+/u', '', $searchStringOrig));

                $trgItems = Item::query()
                    ->select(['*', \DB::raw("similarity(name, ?) as rank")])
                    ->setBindings([$searchString], 'select')
                    ->whereRaw('name % ?', [$searchString]);

                if ($minuses) {
                    foreach ($minuses as $minus) {
                        $trgItems->where(
                            $trgItems->qualifyColumn('name'),
                            'not ilike',
                            sprintf("%%{%s}%%", ltrim($minus, '!'))
                        );
                    }
                }
            }

            if (isset($trgItems)) {
                $items = $tsvItems->union($trgItems);

                $items = Item::query()
                    ->withoutGlobalScope(UserScope::class)
                    ->from(\DB::raw("({$items->toSql()}) as items"))
                    ->setBindings($items->getBindings());
            } else {
                $items = Item::query()
                    ->withoutGlobalScope(UserScope::class)
                    ->from(\DB::raw("({$tsvItems->toSql()}) as items"))
                    ->setBindings($tsvItems->getBindings());
            }
        }

        return $items;
    }
}
