<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class MultiMatchQuery implements Query
{
    public static function create(
        string $query,
        array $fields,
        int|string $fuzziness = 0
    ): static {
        return new self($query, $fields, $fuzziness);
    }

    public function __construct(
        protected string $query,
        protected array $fields,
        protected int|string $fuzziness = 0
    ) {
    }

    public function toArray(): array
    {
        return [
            'multi_match' => [
                'query' => $this->query,
                'fields' => $this->fields,
                'fuzziness' => $this->fuzziness,
            ],
        ];
    }
}
