<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BaseModel extends Model
{
    const NO_FULLTEXT = false;
    use HasFactory;
    /*method join với table dịch của insance model hiện tại*/
    public function scopeAct($q)
    {
        return $q->where('act', 1);
    }
    public function scopeOrd($q)
    {
        return $q->orderByRaw('ord IS NOT NULL, ord ASC')->orderBy('id', 'desc');
    }
    public function scopeSlug($q, $slug, $table = null)
    {
        if ($table == null) {
            return $q->where('slug', $slug);
        }
        return $q->where("$table.slug", $slug);
    }
    public static function getNameById($id)
    {
        $itemService = static::find($id);
        return isset($itemService) ? $itemService->name : '';
    }

    protected function fullTextWildcardLike($term, $isArray = false)
    {
        $reservedSymbols = ['+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
        $words = explode(' ', $term);
        if ($isArray) {
            return $words;
        }
        foreach ($words as $key => $word) {
            if (strlen($word) >= 1) {
                $words[$key] = '+' . $word . '*';
            }
        }
        return implode(' ', $words);
    }

    public function scopeFullTextSearch($query, $columns, $term)
    {
        $minWordLength = $this->getLengthWordCurrent();
        if (mb_strlen($term) < $minWordLength || static::NO_FULLTEXT) {
            $words = $this->fullTextWildcardLike($term, true);
            $query->where(function ($q) use ($words, $columns) {
                foreach ($words as $key => $word) {
                    $word = strtolower($word);
                    if ($key == 0) {
                        $q->whereRaw("LOWER($columns) LIKE BINARY '%$word%'");
                    } else {
                        $q->orWhereRaw("LOWER($columns) LIKE BINARY '%$word%'");
                    }
                }
            })->orderByRaw("CASE
            WHEN " . $columns . " LIKE '" . $term . "%' THEN 1
            WHEN " . $columns . " LIKE '%" . $term . "' THEN 3
            ELSE 2
        END");
        } elseif (count(explode(' ', $term)) > 4) {
            $query->selectRaw(\DB::raw('*,MATCH(' . $columns . ') AGAINST ("' . $term . '") as relevance'))
                ->whereRaw('MATCH(' . $columns . ') AGAINST (?)', [$term])
                ->orderBy('relevance', 'DESC');
        } elseif (count(explode(' ', $term)) >= 2) {
            $words = $this->fullTextWildcardLike($term, true);
            $query->selectRaw(\DB::raw('*'))->where(function ($q) use ($columns, $words) {
                foreach ($words as $key => $word) {
                    $q->orWhereRaw('MATCH(' . $columns . ') AGAINST (? IN BOOLEAN MODE)', [$word]);
                }
            });
        } else {
            $words = $this->fullTextWildcardLike($term);
            $query->selectRaw(\DB::raw('*,MATCH(' . $columns . ') AGAINST ("' . $words . ' IN BOOLEAN MODE") as relevance'))
                ->whereRaw('MATCH(' . $columns . ') AGAINST (? IN BOOLEAN MODE)', [$words])
                ->orderBy('relevance', 'DESC');
        }
        return $query;
    }

    public function scopeFullTextSearchNoRelevance($query, $columns, $term)
    {
        $minWordLength = $this->getLengthWordCurrent();
        if (mb_strlen($term) < $minWordLength || static::NO_FULLTEXT) {
            $words = $this->fullTextWildcardLike($term, true);
            $query->where(function ($q) use ($words, $columns) {
                foreach ($words as $key => $word) {
                    $word = strtolower($word);
                    if ($key == 0) {
                        $q->whereRaw("LOWER($columns) LIKE BINARY '%$word%'");
                    } else {
                        $q->orWhereRaw("LOWER($columns) LIKE BINARY '%$word%'");
                    }
                }
            })->orderByRaw("CASE
            WHEN " . $columns . " LIKE '" . $term . "%' THEN 1
            WHEN " . $columns . " LIKE '%" . $term . "' THEN 3
            ELSE 2
        END");
        } elseif (count(explode(' ', $term)) > 4) {
            $query->whereRaw('MATCH(' . $columns . ') AGAINST (?)', [$term]);
        } elseif (count(explode(' ', $term)) >= 2) {
            $query->whereRaw('MATCH(' . $columns . ') AGAINST (? WITH QUERY EXPANSION)', [$term]);
        } else {
            $words = $this->fullTextWildcardLike($term);
            $query->whereRaw('MATCH(' . $columns . ') AGAINST (? IN BOOLEAN MODE)', [$words]);
        }
        return $query;
    }

    public function getLengthWordCurrent()
    {
        if (!\Cache::has('min_word_length')) {
            \Cache::rememberForever('min_word_length', function () {
                return (int) \DB::select('show variables like "ft_min_word_len"')[0]->Value;
            });
        }
        return \Cache::get('min_word_length');
    }
}
