<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Concerns;

use Blessing\LaravelSearchString\Builder\Builder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

trait SearchString
{
    public function scopeUsingSearchString(QueryBuilder $query, string $input)
    {
        $builder = new Builder();
        $builder->build($query, $input);

        return $query;
    }
}
