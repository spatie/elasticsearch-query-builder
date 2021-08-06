<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class PrefixQuery implements Query
{
    public static function create(
        string $field,
        string | int $query
    ): self {
        return new self($field, $query);
    }

    public function __construct(
        protected string $field,
        protected string | int $query
    ) {
    }

    public function toArray(): array
    {
        return [
            'prefix' => [
                $this->field => [
                    'value' => $this->query,
                ],
            ],
        ];
    }
}
