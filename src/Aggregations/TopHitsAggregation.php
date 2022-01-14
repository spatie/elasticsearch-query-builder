<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class TopHitsAggregation extends Aggregation
{
    protected int $size;

    protected ?Sort $sort = null;

    protected ?array $source;

    public static function create(string $name, int $size, ?Sort $sort = null): static
    {
        return new self($name, $size, $sort);
    }

    public function __construct(
        string $name,
        int $size,
        ?Sort $sort = null
    ) {
        $this->name = $name;
        $this->size = $size;
        $this->sort = $sort;
    }

    public function source(array $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function payload(): array
    {
        $parameters = [
            'size' => $this->size,
        ];

        if ($this->sort) {
            $parameters['sort'] = [$this->sort->toArray()];
        }

        if ($this->source) {
            $parameters['_source'] = $this->source;
        }

        return [
            'top_hits' => $parameters,
        ];
    }
}
