<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery;

use Spatie\ElasticsearchQueryBuilder\SortCollection;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class InnerHits
{
    public static function create(): self
    {
        return new InnerHits();
    }

    public function __construct(
        protected ?int $from = null,
        protected ?int $size = null,
        protected ?string $name = null,
        protected ?SortCollection $sorts = null
    ) {
    }

    public function from(int $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function addSort(Sort $sort): self
    {
        if (! $this->sorts) {
            $this->sorts = new SortCollection();
        }

        $this->sorts->add($sort);

        return $this;
    }

    public function getPayload(): array
    {
        return array_filter(
            [
                'from' => $this->from,
                'size' => $this->size,
                'name' => $this->name,
                'sort' => $this->sorts?->toArray(),
            ]
        );
    }
}
