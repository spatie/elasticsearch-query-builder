<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class TermsQuery implements Query
{
    protected string $field;

    protected array $values;

    public static function create(string $field, array $values): static
    {
        return new self($field, $values);
    }

    public function __construct(string $field, array $values)
    {
        $this->field = $field;
        $this->values = $values;
    }

    public function toArray(): array
    {
        return [
            'terms' => [
                $this->field => $this->values,
            ],
        ];
    }
}
