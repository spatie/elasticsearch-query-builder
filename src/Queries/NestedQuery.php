<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

use Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery\InnerHits;

class NestedQuery implements Query
{
    public const SCORE_MODE_AVG = 'avg';
    public const SCORE_MODE_MAX = 'max';
    public const SCORE_MODE_MIN = 'min';
    public const SCORE_MODE_NONE = 'none';
    public const SCORE_MODE_SUM = 'sum';

    public static function create(string $path, Query $query): self
    {
        return new self($path, $query);
    }

    public function __construct(
        protected string $path,
        protected Query $query,
        protected ?string $scoreMode = null,
        protected ?bool $ignoreUnmapped = null,
        protected ?InnerHits $innerHits = null
    ) {
    }

    public function scoreMode(string $scoreMode): self
    {
        $this->scoreMode = $scoreMode;

        return $this;
    }

    public function ignoreUnmapped(bool $ignoreUnmapped): self
    {
        $this->ignoreUnmapped = $ignoreUnmapped;

        return $this;
    }

    public function innerHits(InnerHits $innerHits): self
    {
        $this->innerHits = $innerHits;

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
                    'ignore_unmapped' => $this->ignoreUnmapped,
                    'inner_hits' => $this->innerHits?->toArray(),
                ]
            ),
        ];
    }
}
