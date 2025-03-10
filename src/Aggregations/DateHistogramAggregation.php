<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

class DateHistogramAggregation extends Aggregation
{
    use WithAggregations;

    protected string $field;

    protected string $calendarInterval;

    public static function create(string $name, string $field, string $calendarInterval, Aggregation ...$aggregations): self
    {
        return new self($name, $field, $calendarInterval, ...$aggregations);
    }

    public function __construct(
        string $name,
        string $field,
        string $calendarInterval,
        Aggregation ...$aggregations
    ) {
        $this->name = $name;
        $this->field = $field;
        $this->calendarInterval = $calendarInterval;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    public function payload(): array
    {
        $payload = [
            'date_histogram' => [
                'field' => $this->field,
                'calendar_interval' => $this->calendarInterval,
            ],
        ];

        if (! $this->aggregations->isEmpty()) {
            $payload['aggs'] = $this->aggregations->toArray();
        }

        return $payload;
    }
}
