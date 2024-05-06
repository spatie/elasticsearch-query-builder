<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class TermQuery implements Query
{
    protected string $field;

    protected bool|int|string $value;

    public static function create(string $field, bool|int|string $value): static
    {
        return new self($field, $value);
    }

    public function __construct(string $field, bool|int|string $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [
            'term' => [
                $this->field => $this->value,
            ],
        ];
    }
}
