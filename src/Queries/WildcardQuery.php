<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class WildcardQuery implements Query
{
    public static function create(string $field, string $value)
    {
        return new self($field, $value);
    }

    public function __construct(
        protected string $field,
        protected string $value
    ) {
    }

    public function toArray(): array
    {
        return [
            'wildcard' => [
                $this->field => [
                    'value' => $this->value,
                ],
            ],
        ];
    }
}
