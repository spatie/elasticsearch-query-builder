<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

use Spatie\ElasticsearchQueryBuilder\Exceptions\InvalidOperatorValue;

class MultiMatchQuery implements Query
{
    public const TYPE_BEST_FIELDS = 'best_fields';
    public const TYPE_MOST_FIELDS = 'most_fields';
    public const TYPE_CROSS_FIELDS = 'cross_fields';
    public const TYPE_PHRASE = 'phrase';
    public const TYPE_PHRASE_PREFIX = 'phrase_prefix';
    public const TYPE_BOOL_PREFIX = 'bool_prefix';
    protected const VALID_OPERATORS = ['and', 'or'];

    public static function create(
        string $query,
        array $fields,
        int | string | null $fuzziness = null,
        string | null $type = null,
        string | null $operator = null,
        float | null $boost = null,
        int | null $prefixLength = null,
        int | null $maxExpansions = null,
    ): static {
        return new self($query, $fields, $fuzziness, $type, $operator, $boost, $prefixLength, $maxExpansions);
    }

    public function __construct(
        protected string $query,
        protected array $fields,
        protected int | string | null $fuzziness = null,
        protected string | null $type = null,
        protected string | null $operator = null,
        protected float | null $boost = null,
        protected int | null $prefixLength = null,
        protected int | null $maxExpansions = null,
    ) {
        if ($operator && ! in_array(strtolower($operator), self::VALID_OPERATORS)) {
            throw new InvalidOperatorValue;
        }
    }

    public function toArray(): array
    {
        $multiMatch = [
            'multi_match' => [
                'query' => $this->query,
                'fields' => $this->fields,
            ],
        ];

        if ($this->fuzziness) {
            $multiMatch['multi_match']['fuzziness'] = $this->fuzziness;
        }

        if ($this->type) {
            $multiMatch['multi_match']['type'] = $this->type;
        }

        if ($this->operator) {
            $multiMatch['multi_match']['operator'] = $this->operator;
        }

        if ($this->boost !== null) {
            $multiMatch['multi_match']['boost'] = $this->boost;
        }

        if ($this->prefixLength !== null) {
            $multiMatch['multi_match']['prefix_length'] = $this->prefixLength;
        }

        if ($this->maxExpansions !== null) {
            $multiMatch['multi_match']['max_expansions'] = $this->maxExpansions;
        }

        return $multiMatch;
    }
}
