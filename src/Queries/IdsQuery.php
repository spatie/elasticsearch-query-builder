<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class IdsQuery implements Query
{
    public static function create(
        array $values
    ): self {
        return new self($values);
    }

    public function __construct(
        protected array $values
    ) {
    }

    public function toArray(): array
    {
        return [
            'ids' => [
                'values' => $this->values,
            ],
        ];
    }
}
