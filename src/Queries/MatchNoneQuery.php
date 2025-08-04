<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class MatchNoneQuery implements Query
{
    public static function create(): self
    {
        return new self();
    }

    public function __construct()
    {
    }

    public function toArray(): array
    {
        return [
            'match_none' => new \stdClass(),
        ];
    }
}