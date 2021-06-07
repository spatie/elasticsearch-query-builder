<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class MultiMatchQuery implements Query
{
    public static function create(
        string $query,
        array $fields,
        int|string|null $fuzziness = null
    ): static {
        return new self($query, $fields, $fuzziness);
    }

    public function __construct(
        protected string $query,
        protected array $fields,
        protected int|string|null $fuzziness = null
    ) {
    }

    public function toArray(): array
    {
        $multiMatch = [
            'multi_match' => [
                'query' => $this->query,
                'fields' => $this->fields,
            ],
        ];

        if($this->fuzziness) {
            $multiMatch['multi_match']['fuzziness'] = $this->fuzziness;
        }

        return $multiMatch;
    }
}
