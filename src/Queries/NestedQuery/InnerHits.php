<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery;

use Spatie\ElasticsearchQueryBuilder\SortCollection;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class InnerHits
{
    public static function create(string $name): self
    {
        return new InnerHits($name);
    }

    /**
     * @param string[]|null $fields
     */
    public function __construct(
        protected string $name,
        protected ?int $from = null,
        protected ?int $size = null,
        protected ?SortCollection $sorts = null,
        protected ?array $fields = null
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

    /**
     * @param string[] $fields
     */
    public function fields(array $fields): self
    {
        $this->fields = $fields;

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

    public function toArray(): array
    {
        return array_filter(
            [
                'from' => $this->from,
                'size' => $this->size,
                'name' => $this->name,
                'sort' => $this->sorts?->toArray(),
                '_source' => $this->fields,
            ]
        );
    }
}
