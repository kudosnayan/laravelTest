<?php

namespace App\Support\Traits;

use Illuminate\Support\Arr;

trait FieldSearch
{
    /**
     * Searches for a specific term in the given fields of a query.
     *
     * @param  mixed  $query The query to search in.
     * @param  array  $fields The fields to search in.
     * @param  array  $term The term to search for.
     * @return None
     *
     * @throws None
     */
    public static function searchResult($query, $fields, $term)
    {
        return collect($fields)->each(function ($field) use ($query, $term) {
            $value = Arr::get($term, $field);
            if (! is_null($value)) {
                if (! is_numeric($value)) {
                    if (Arr::exists($term, 'start_date_time') && Arr::exists($term, 'end_start_time')) {
                        $startDate = $term['start_date_time'];
                        $endDate = $term['end_start_time'];
                        $query->whereBetween($field, [$startDate, $endDate]);
                    } else {
                        $query->where($field, 'like', '%'.$value.'%');
                    }
                } elseif (is_numeric($value)) {
                    $query->where($field, $value);
                }
            }
        });
    }
}
