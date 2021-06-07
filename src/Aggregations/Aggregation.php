<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

abstract class Aggregation
{
    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }

    abstract public function toArray(): array;
}
