<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class TermsQuery implements Query
{
    protected string $field;

    protected array $value;

    public static function create(string $field, array $value, null | float $boost = null): static
    {
        return new self($field, $value, $boost);
    }

    public function __construct(string $field, array $value, protected null | float $boost = null)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function toArray(): array
    {
        $terms = [
            'terms' => [
                $this->field => $this->value,
            ],
        ];

        if ($this->boost !== null) {
            $terms['terms']['boost'] = $this->boost;
        }

        return $terms;
    }
}
