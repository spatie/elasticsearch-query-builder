<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class RegexpQuery implements Query
{
    public static function create(
        string $field,
        string $value,
        ?string $flags = null,
        ?int $maxDeterminizedStates = null,
        ?float $boost = null
    ): self {
        return new self($field, $value, $flags, $maxDeterminizedStates, $boost);
    }

    public function __construct(
        protected string $field,
        protected string $value,
        protected ?string $flags = null,
        protected ?int $maxDeterminizedStates = null,
        protected ?float $boost = null
    ) {
    }

    public function toArray(): array
    {
        $regexp = [
            'regexp' => [
                $this->field => [
                    'value' => $this->value,
                ],
            ],
        ];

        if ($this->flags !== null) {
            $regexp['regexp'][$this->field]['flags'] = $this->flags;
        }

        if ($this->maxDeterminizedStates !== null) {
            $regexp['regexp'][$this->field]['max_determinized_states'] = $this->maxDeterminizedStates;
        }

        if ($this->boost !== null) {
            $regexp['regexp'][$this->field]['boost'] = $this->boost;
        }

        return $regexp;
    }
}
