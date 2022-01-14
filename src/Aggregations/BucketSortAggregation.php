<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class BucketSortAggregation extends Aggregation
{
    protected Sort $sort;

    public static function create(string $name, Sort $sort): self
    {
        return new self($name, $sort);
    }

    public function __construct(string $name, Sort $sort)
    {
        $this->name = $name;
        $this->sort = $sort;
    }

    public function payload(): array
    {
        return [
            'bucket_sort' => [
                'sort' => $this->sort->toArray(),
            ],
        ];
    }
}
