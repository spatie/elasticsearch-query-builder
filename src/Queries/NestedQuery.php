<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class NestedQuery implements Query
{
    public static function create(string $path, Query $query): self
    {
        return new self($path, $query);
    }

    public function __construct(
        protected string $path,
        protected Query $query,
        protected ?string $scoreMode = null
    ) {
    }

    public function scoreMode(string $scoreMode): self
    {
        $this->scoreMode = $scoreMode;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'nested' => array_filter(
                [
                    'path' => $this->path,
                    'query' => $this->query->toArray(),
                    'score_mode' => $this->scoreMode,
                ]
            ),
        ];
    }
}
