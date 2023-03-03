<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class MatchQuery implements Query
{
    public static function create(
        string $field,
        string | int | bool $query,
        null | string | int $fuzziness = null
    ): self {
        return new self($field, $query, $fuzziness);
    }

    public function __construct(
        protected string $field,
        protected string | int | bool $query,
        protected null | string | int $fuzziness = null
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

        return $match;
    }
}
