<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

class DateHistogramAggregation extends Aggregation
{
    use WithAggregations;

    protected string $field = '';

    protected string $script = '';

    protected int | string $interval = '';

    protected string $format = '';

    protected ?array $extended_bounds = null;

    protected string $timezone = '';

    protected ?array $order = null;

    public function __construct(string $name, Aggregation ...$aggregations)
    {
        $this->name = $name;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    public static function create(string $name, Aggregation ...$aggregations): self
    {
        return new self($name, ...$aggregations);
    }

    public function field(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function script(string $script): self
    {
        $this->script = $script;

        return $this;
    }

    public function interval(int|string $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function extendedBounds(array $extended_bounds): self
    {
        $this->extended_bounds = $extended_bounds;

        return $this;
    }

    public function timezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function order(array $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function payload(): array
    {
        $parameters = [
            'field' => $this->field,
            'script' => $this->script,
            'interval' => $this->interval,
            'format' => $this->format,
            'extended_bounds' => $this->extended_bounds,
            'time_zone' => $this->timezone,
            'order' => $this->order,
        ];

        $aggregation = [
            'date_histogram' => array_filter($parameters),
        ];

        if (! $this->aggregations->isEmpty()) {
            $aggregation['aggs'] = $this->aggregations->toArray();
        }

        return $aggregation;
    }
}
