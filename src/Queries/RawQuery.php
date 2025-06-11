<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class RawQuery implements Query
{
    protected array $query;

    public function __construct(array $query)
    {
        $this->query = $query;
    }

    public static function create(array $query): static
    {
        return new self($query);
    }

    public function toArray(): array
    {
        return $this->query;
    }
}
