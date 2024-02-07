<?php

namespace App\Models\Traits\Scopes;

use App\Models\Traits\Enum\UserEnum;
use App\Support\Traits\FieldSearch;
use Illuminate\Support\Arr;

/**
 * Class UserScope.
 */
trait UserScope
{
    use FieldSearch;

    public function scopeFilterAdmin($query, $term)
    {
        return $query->when(Arr::hasAny($term, UserEnum::SEARCH_PARAM), function ($query) use ($term) {
            FieldSearch::searchResult($query, UserEnum::SEARCH_PARAM, $term);
        });
    }

    /**
     * @return mixed
     */
    public function scopeAdminSort($query, $term)
    {
        return $query->when(Arr::has($term, ['sort', 'sort_order']), function ($query) use ($term) {
            $query->orderBy($term['sort'], $term['sort_order']);
        });
    }
}
