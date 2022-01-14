<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

use Spatie\ElasticsearchQueryBuilder\Exceptions\BoolQueryTypeDoesNotExist;

class BoolQuery implements Query
{
    protected array $must = [];
    protected array $filter = [];
    protected array $should = [];
    protected array $must_not = [];
    protected ?int $minimum_should_match = null;

    public static function create(): static
    {
        return new self();
    }

    public function add(Query $query, string $type = 'must'): static
    {
        if (! in_array($type, ['must', 'filter', 'should', 'must_not'])) {
            throw new BoolQueryTypeDoesNotExist($type);
        }

        $this->$type[] = $query;

        return $this;
    }

    public function minimumShouldMatch(int $minimum_should_match): self
    {
        $this->minimum_should_match = $minimum_should_match;

        return $this;
    }

    public function toArray(): array
    {
        $bool = [
            'must' => array_map(fn (Query $query) => $query->toArray(), $this->must),
            'filter' => array_map(fn (Query $query) => $query->toArray(), $this->filter),
            'should' => array_map(fn (Query $query) => $query->toArray(), $this->should),
            'must_not' => array_map(fn (Query $query) => $query->toArray(), $this->must_not),
            'minimum_should_match' => $this->minimum_should_match,
        ];

        return [
            'bool' => array_filter($bool),
        ];
    }
}
