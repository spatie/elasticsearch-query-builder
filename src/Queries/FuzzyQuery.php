<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class FuzzyQuery implements Query
{
    public static function create(
        string $field,
        string $value,
        null | string | int $fuzziness = null,
        ?int $maxExpansions = null,
        ?int $prefixLength = null,
        ?bool $transpositions = null,
        ?float $boost = null
    ): self {
        return new self($field, $value, $fuzziness, $maxExpansions, $prefixLength, $transpositions, $boost);
    }

    public function __construct(
        protected string $field,
        protected string $value,
        protected null | string | int $fuzziness = null,
        protected ?int $maxExpansions = null,
        protected ?int $prefixLength = null,
        protected ?bool $transpositions = null,
        protected ?float $boost = null
    ) {
    }

    public function toArray(): array
    {
        $fuzzy = [
            'fuzzy' => [
                $this->field => [
                    'value' => $this->value,
                ],
            ],
        ];

        if ($this->fuzziness !== null) {
            $fuzzy['fuzzy'][$this->field]['fuzziness'] = $this->fuzziness;
        }

        if ($this->maxExpansions !== null) {
            $fuzzy['fuzzy'][$this->field]['max_expansions'] = $this->maxExpansions;
        }

        if ($this->prefixLength !== null) {
            $fuzzy['fuzzy'][$this->field]['prefix_length'] = $this->prefixLength;
        }

        if ($this->transpositions !== null) {
            $fuzzy['fuzzy'][$this->field]['transpositions'] = $this->transpositions;
        }

        if ($this->boost !== null) {
            $fuzzy['fuzzy'][$this->field]['boost'] = $this->boost;
        }

        return $fuzzy;
    }
}
