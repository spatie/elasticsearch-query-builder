<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class MatchPhraseQuery implements Query
{
    public static function create(
        string      $field,
        string      $value,
        int|null    $slop = null,
        string|null $zeroTermsQuery = null,
        string|null $analyzer = null
    ): self {
        return new self($field, $value, $slop, $zeroTermsQuery, $analyzer);
    }

    public function __construct(
        protected string      $field,
        protected string      $value,
        protected int | null    $slop = null,
        protected string | null $zeroTermsQuery = null,
        protected string | null $analyzer = null
    ) {
    }

    public function toArray(): array
    {
        $match = [
            'match_phrase' => [
                $this->field => [
                    'query' => $this->value,
                ],
            ],
        ];

        if ($this->slop) {
            $match['match_phrase'][$this->field]['slop'] = $this->slop;
        }

        if ($this->zeroTermsQuery) {
            $match['match_phrase'][$this->field]['zero_terms_query'] = $this->zeroTermsQuery;
        }

        if ($this->analyzer) {
            $match['match_phrase'][$this->field]['analyzer'] = $this->analyzer;
        }

        return $match;
    }
}
