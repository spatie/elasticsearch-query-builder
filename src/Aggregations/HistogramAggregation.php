<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithMissing;

class HistogramAggregation extends Aggregation
{
    use WithMissing;
    use WithAggregations;

    protected string $field;
    protected float $interval;
    protected ?int $minDocCount = null;
    protected ?array $extendedBounds = null;
    protected ?array $hardBounds = null;
    protected ?float $offset = null;

    public static function create(string $name, string $field, float $interval): self
    {
        return new self($name, $field, $interval);
    }

    public function __construct(string $name, string $field, float $interval)
    {
        $this->name = $name;
        $this->field = $field;
        $this->interval = $interval;
        $this->aggregations = new AggregationCollection();
    }

    public function minDocCount(int $minDocCount): self
    {
        $this->minDocCount = $minDocCount;

        return $this;
    }

    public function extendedBounds(float $min, float $max): self
    {
        $this->extendedBounds = [
            'min' => $min,
            'max' => $max,
        ];

        return $this;
    }

    public function hardBounds(float $min, float $max): self
    {
        $this->hardBounds = [
            'min' => $min,
            'max' => $max,
        ];

        return $this;
    }

    public function offset(float $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function payload(): array
    {
        $parameters = [
            'field' => $this->field,
            'interval' => $this->interval,
        ];

        if ($this->minDocCount !== null) {
            $parameters['min_doc_count'] = $this->minDocCount;
        }

        if ($this->extendedBounds !== null) {
            $parameters['extended_bounds'] = $this->extendedBounds;
        }

        if ($this->hardBounds !== null) {
            $parameters['hard_bounds'] = $this->hardBounds;
        }

        if ($this->offset !== null) {
            $parameters['offset'] = $this->offset;
        }

        if ($this->missing !== null) {
            $parameters['missing'] = $this->missing;
        }

        $aggregation = [
            'histogram' => $parameters,
        ];

        if (! $this->aggregations->isEmpty()) {
            $aggregation['aggs'] = $this->aggregations->toArray();
        }

        return $aggregation;
    }
}
