<?php

namespace App\Services\Search;

use App\Models\Dictionary;
use Illuminate\Database\Eloquent\Builder;

class SearchTerms
{
    /** Match every word, allowing its dictionary meanings and Persian keyboard equivalent. */
    public static function apply(Builder $query, string $column, string $input, bool $splitWords = true): Builder
    {
        $words = $splitWords ? preg_split('/\s+/u', trim($input), -1, PREG_SPLIT_NO_EMPTY) : [trim($input)];
        $keys = array_unique(array_merge($words, mb_str_split(strtolower($input))));
        $dictionary = Dictionary::whereIn('original_word', $keys)->orderBy('id')->get();
        $meanings = $dictionary->groupBy('original_word');
        $keyboard = [];

        foreach ($dictionary as $entry) {
            // Only single English keyboard keys with Persian values form the layout map.
            if (preg_match('/^[\x21-\x7E]$/D', $entry->original_word)
                && preg_match('/^[\x{0600}-\x{06FF}\x{200C}]+$/u', $entry->mean)) {
                $keyboard[$entry->original_word] = $entry->mean;
                $keyboard[strtoupper($entry->original_word)] = $entry->mean;
            }
        }

        return $query->where(function (Builder $all) use ($words, $meanings, $keyboard, $column) {
            foreach ($words as $word) {
                $alternatives = [$word, strtr($word, $keyboard)];
                foreach ($meanings->get($word, collect()) as $entry) {
                    if ($entry->mean !== '') {
                        $alternatives[] = $entry->mean;
                    }
                }

                $all->where(function (Builder $term) use ($alternatives, $column) {
                    foreach (array_unique($alternatives) as $alternative) {
                        $term->orWhere($column, 'like', '%'.$alternative.'%');
                    }
                });
            }
        });
    }
}
