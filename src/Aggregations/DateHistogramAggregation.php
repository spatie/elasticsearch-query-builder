<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

class DateHistogramAggregation extends Aggregation
{
    protected string $field;
    protected string $calendarInterval;

    public static function create(string $name, string $field, string $calendarInterval): self
    {
        return new self($name, $field, $calendarInterval);
    }

    public function __construct(string $name, string $field, string $calendarInterval)
    {
        $this->name = $name;
        $this->field = $field;
        $this->calendarInterval = $calendarInterval;
    }

    public function payload(): array
    {
        $parameters = [
            'field' => $this->field,
            'calendar_interval' => $this->calendarInterval,
        ];

        return [
            'date_histogram' => $parameters,
        ];
    }
}
