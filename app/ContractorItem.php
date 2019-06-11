<?php

namespace App;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractorItem extends Model
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

            $tsvItems = ContractorItem::query()->select([
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

            if ($contractorId) {
                $tsvItems->where([$tsvItems->qualifyColumn('contractor_id') => $contractorId]);
            }

            /** pg_trgm make no sense with short search queries */
            if (mb_strlen($searchStringOrig) > 1) {
                preg_match_all('/\s!\w+/u', $searchStringOrig, $matches);
                $minuses = (isset($matches[0]) && (count($matches[0]) > 0)) ? $matches[0] : null;
                $searchString = trim(preg_replace('/\s!\w+/u', '', $searchStringOrig));

                $trgItems = ContractorItem::query()
                    ->select(['*', \DB::raw("similarity(name, ?) as rank")])
                    ->setBindings([$searchString], 'select')
                    ->whereRaw('name % ?', [$searchString]);

                if ($minuses) {
                    foreach ($minuses as $minus) {
                        $trgItems->where(
                            $trgItems->qualifyColumn('name'),
                            'not ilike',
                            sprintf("%%%s%%", ltrim($minus, '! '))
                        );
                    }
                }

                if ($contractorId) {
                    $trgItems->where([$tsvItems->qualifyColumn('contractor_id') => $contractorId]);
                }

            }

            if (isset($trgItems)) {
                $items = $tsvItems->union($trgItems);

                $items = ContractorItem::query()
                    ->withoutGlobalScope(UserScope::class)
                    ->from(\DB::raw("({$items->toSql()}) as contractor_items"))
                    ->setBindings($items->getBindings());
            } else {
                $items = ContractorItem::query()
                    ->withoutGlobalScope(UserScope::class)
                    ->from(\DB::raw("({$tsvItems->toSql()}) as contractor_items"))
                    ->setBindings($tsvItems->getBindings());
            }
        }

        return $items;

    }

}
