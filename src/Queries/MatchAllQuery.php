<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class MatchAllQuery implements Query
{
    public static function create(
        ?float $boost = null
    ): self {
        return new self($boost);
    }

    public function __construct(
        protected ?float $boost = null
    ) {
    }

    public function toArray(): array
    {
        $query = [
            'match_all' => new \stdClass(),
        ];

        if ($this->boost !== null) {
            $query['match_all'] = [
                'boost' => $this->boost,
            ];
        }

        return $query;
    }
}