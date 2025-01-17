<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class MatchQuery implements Query
{
    public static function create(
        string $field,
        string | int $query,
        null | string | int $fuzziness = null,
        null | float $boost = null,
        null | string $operator = 'or'
    ): self {
        return new self($field, $query, $fuzziness, $boost);
    }

    public function __construct(
        protected string $field,
        protected string | int $query,
        protected null | string | int $fuzziness = null,
        protected null | float $boost = null,
        protected null | string $operator = 'or'
    ) {
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
