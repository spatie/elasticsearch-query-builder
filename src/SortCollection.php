<?php

namespace Spatie\ElasticsearchQueryBuilder;

use Spatie\ElasticsearchQueryBuilder\Sorts\Sorting;

class SortCollection
{
    protected array $sorts;

    public function __construct(Sorting ...$sorts)
    {
        $this->sorts = $sorts;
    }

    public function add(Sorting $sort): self
    {
        $this->sorts[] = $sort;

        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->sorts);
    }

    public function toArray(): array
    {
        $sorts = [];

        foreach ($this->sorts as $sort) {
            $sorts[] = $sort->toArray();
        }

        return $sorts;
    }
}
