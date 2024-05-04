<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts;

use Spatie\ElasticsearchQueryBuilder\Queries\Query;
use Spatie\ElasticsearchQueryBuilder\Sorts\Concerns\HasMode;

class NestedSort implements Sorting
{
    use HasMode;

    public function __construct(
        protected string $path,
        protected string $field,
        protected string $order,
        protected ?Query $filter,
        ?string $mode,
        protected ?NestedSort $nested
    ) {
        $this->mode = $mode;
    }

    public static function create(
        string $path,
        string $field,
        string $order,
        ?Query $filter = null,
        ?string $mode = null,
        ?NestedSort $nested = null
    ): self {
        return new self(
            $path,
            $field,
            $order,
            $filter,
            $mode,
            $nested
        );
    }

    public function toArray(): array
    {
        return [
            $this->field => array_filter(
                [
                    'order' => $this->order,
                    'mode' => $this->mode,
                    'nested' => array_filter(
                        [
                            'path' => $this->path,
                            'filter' => $this->filter?->toArray(),
                            'nested' => $this->nested?->toArray()
                        ]
                    ),
                ]
            )
        ];
    }
}
