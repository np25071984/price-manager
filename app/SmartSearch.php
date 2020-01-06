<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmartSearch extends Model
{
    public static function getTsvQuery($searchString) {
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

        $tsvItems = self::query();

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

        $tsvItems->orderByRaw("(ts_rank(tsvector_token, "
                . "ts_rewrite(to_tsquery('english', coalesce(?, '')), 'SELECT t, s FROM aliases')) "
                . "+ ts_rank(tsvector_token, "
                . "ts_rewrite(to_tsquery('russian', coalesce(?, '')), 'SELECT t, s FROM aliases'))"
                . ") DESC",
            [$englishQuery, $russianQuery]
        );

        return $tsvItems;
    }

    public static function getTrgmQuery($searchStringOrig) {
        /** pg_trgm make no sense with short search queries */
        if (mb_strlen($searchStringOrig) > 16) {
            preg_match_all('/\s!\w+/u', $searchStringOrig, $matches);
            $minuses = (isset($matches[0]) && (count($matches[0]) > 0)) ? $matches[0] : null;
            $searchString = trim(preg_replace('/\s!\w+/u', '', $searchStringOrig));

            $trgmItems = self::query()
                ->whereRaw('name % ?', [$searchString]);

            if ($minuses) {
                foreach ($minuses as $minus) {
                    $trgmItems->where(
                        $trgmItems->qualifyColumn('name'),
                        'not ilike',
                        sprintf("%%%s%%", trim($minus, '! '))
                    );
                }
            }

            $trgmItems->orderByRaw("similarity(name, ?) DESC", [$searchString]);
        } else {
            $trgmItems = null;
        }

        return $trgmItems;
    }

    public static function getSmartSearchQuery($tsvQuery, $trgmQuery, $alias) {
        if ($trgmQuery) {
            $items = $tsvQuery->union($trgmQuery);

            $items = self::query()
                ->from(\DB::raw("({$items->toSql()}) as {$alias}"))
                ->setBindings($items->getBindings());
        } else {
            $items = self::query()
                ->from(\DB::raw("({$tsvQuery->toSql()}) as {$alias}"))
                ->setBindings($tsvQuery->getBindings());
        }

        return $items;
    }

}
