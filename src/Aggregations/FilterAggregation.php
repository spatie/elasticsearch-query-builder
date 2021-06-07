<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;
use Spatie\ElasticsearchQueryBuilder\Queries\Query;

class FilterAggregation extends Aggregation
{
    use WithAggregations;

    protected Query $filter;

    public static function create(
        string $name,
        Query $filter,
        Aggregation ...$aggregations
    ): self {
        return new self($name, $filter, ...$aggregations);
    }

    public function __construct(
        string $name,
        Query $filter,
        Aggregation ...$aggregations
    ) {
        $this->name = $name;
        $this->filter = $filter;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    public function toArray(): array
    {
        return [
            'filter' => $this->filter->toArray(),
            'aggs' => $this->aggregations->toArray(),
        ];
    }
}
