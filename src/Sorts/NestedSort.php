<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts;

use Spatie\ElasticsearchQueryBuilder\Queries\Query;
use Spatie\ElasticsearchQueryBuilder\Sorts\Concerns\HasMissing;
use Spatie\ElasticsearchQueryBuilder\Sorts\Concerns\HasMode;
use Spatie\ElasticsearchQueryBuilder\Sorts\Concerns\HasUnmappedType;

class NestedSort implements Sorting
{
    use HasMissing;
    use HasUnmappedType;
    use HasMode;

    public function __construct(
        protected string $path,
        protected string $field,
        protected string $order,
        protected ?Query $filter,
        protected ?NestedSort $nested,
        protected ?int $maxChildren
    ) {
    }

    public static function create(
        string $path,
        string $field,
        string $order,
    ): self {
        return new self(
            $path,
            $field,
            $order,
            null,
            null,
            null
        );
    }

    public function filter(Query $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    public function nested(NestedSort $nested): self
    {
        $this->nested = $nested;

        return $this;
    }

    public function maxChildren(int $maxChildren): self
    {
        $this->maxChildren = $maxChildren;

        return $this;
    }

    public function toArray(): array
    {
        $payload = array_filter(
            [
                'order' => $this->order,
                'mode' => $this->mode,
                'unmapped_type' => $this->unmappedType,
                'nested' => array_filter(
                    [
                        'path' => $this->path,
                        'filter' => $this->filter?->toArray(),
                        'nested' => $this->nested?->toArray(),
                        'max_children' => $this->maxChildren,
                    ]
                ),
            ]
        );

        // missing can be empty string or zero value
        if ($this->missing !== null) {
            $payload['missing'] = $this->missing;
        }

        return [
            $this->field => $payload,
        ];
    }
}
