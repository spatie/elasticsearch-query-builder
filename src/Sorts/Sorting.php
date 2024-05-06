<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts;

interface Sorting
{
    public const ASC = 'asc';
    public const DESC = 'desc';

    public function toArray(): array;
}
