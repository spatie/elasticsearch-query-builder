<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

class NestedAggregation extends Aggregation
{
    use WithAggregations;

    protected string $path;

    public static function create(
        string $name,
        string $path,
        Aggregation ...$aggregations
    ): self {
        return new self($name, $path, ...$aggregations);
    }

    public function __construct(
        string $name,
        string $path,
        Aggregation ...$aggregations
    ) {
        $this->name = $name;
        $this->path = $path;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    public function payload(): array
    {
        $aggregation = [
            'nested' => [
                'path' => $this->path,
            ],
        ];

        if (! $this->aggregations->isEmpty()) {
            $aggregation['aggs'] = $this->aggregations->toArray();
        }

        return $aggregation;
    }
}
