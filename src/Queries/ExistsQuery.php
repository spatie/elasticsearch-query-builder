<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class ExistsQuery implements Query
{
    public static function create(
        string $field
    ): self {
        return new self($field);
    }

    public function __construct(
        protected string $field
    ) {
    }

    public function toArray(): array
    {
        return [
            'exists' => [
                'field' => $this->field,
            ],
        ];
    }
}
