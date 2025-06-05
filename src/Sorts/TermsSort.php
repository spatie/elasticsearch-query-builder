<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts;

class TermsSort implements Sorting
{
    /**
     * @param string $fieldOrAggName can be a field name (terms agg), a sub-aggregation name, or `_key` or `_count`
     */
    public static function create(string $fieldOrAggName, string $order = self::DESC): static
    {
        return new self($fieldOrAggName, $order);
    }

    /**
     * @param string $fieldOrAggName can be a field name (terms agg), a sub-aggregation name, or `_key` or `_count`
     */
    public function __construct(protected string $fieldOrAggName, protected string $order)
    {
    }

    public function toArray(): array
    {
        return [
            $this->fieldOrAggName => $this->order,
        ];
    }
}
