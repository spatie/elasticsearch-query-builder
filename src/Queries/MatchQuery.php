<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

use Spatie\ElasticsearchQueryBuilder\Exceptions\InvalidOperatorValue;

class MatchQuery implements Query
{
    protected const VALID_OPERATORS = ['and', 'or'];

    public static function create(
        string $field,
        string | int $query,
        null | string | int $fuzziness = null,
        null | float $boost = null,
        null | string $operator = 'or'
    ): self {
        return new self($field, $query, $fuzziness, $boost, $operator);
    }

    public function __construct(
        protected string $field,
        protected string | int $query,
        protected null | string | int $fuzziness = null,
        protected null | float $boost = null,
        protected null | string $operator = 'or'
    ) {
        if (! in_array(strtolower($operator), self::VALID_OPERATORS)) {
            throw new InvalidOperatorValue;
        }
    }

    public function toArray(): array
    {
        $match = [
            'match' => [
                $this->field => [
                    'query' => $this->query,
                ],
            ],
        ];

        if ($this->fuzziness) {
            $match['match'][$this->field]['fuzziness'] = $this->fuzziness;
        }

        if ($this->boost) {
            $match['match'][$this->field]['boost'] = $this->boost;
        }

        if ($this->operator) {
            $match['match'][$this->field]['operator'] = $this->operator;
        }

        return $match;
    }
}
