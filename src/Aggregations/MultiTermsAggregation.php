<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithMissing;
use Spatie\ElasticsearchQueryBuilder\Sorts\TermsSort;

class MultiTermsAggregation extends Aggregation
{
    use WithMissing;
    use WithAggregations;

    /** @var string[] */
    protected array $fields;

    protected ?int $size = null;

    /** @var ?TermsSort[] */
    protected ?array $order = null;

    /**
     * @param string[] $fields
     */
    public static function create(
        string $name,
        array $fields,
        ?int $size = null,
        TermsSort|array|null $order = null
    ): self {
        return new self($name, $fields, $size, $order);
    }

    /**
     * @param string[] $fields
     */
    public function __construct(
        string $name,
        array $fields,
        ?int $size = null,
        TermsSort|array|null $order = null
    ) {
        $this->name = $name;
        $this->fields = $fields;
        $this->aggregations = new AggregationCollection();
        $this->size = $size;
        $this->order = ($order instanceof TermsSort) ? [$order] : $order;
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param TermsSort|TermsSort[] $order
     */
    public function order(TermsSort|array $order): self
    {
        $this->order = ($order instanceof TermsSort) ? [$order] : $order;

        return $this;
    }

    public function addSort(TermsSort $sort): self
    {
        if (! $this->order) {
            $this->order = [];
        }
        $this->order[] = $sort;

        return $this;
    }

    public function payload(): array
    {
        $terms = [];

        foreach ($this->fields as $field) {
            $terms[] = ['field' => $field];
        }

        $parameters = [
            'terms' => $terms,
        ];

        if ($this->size) {
            $parameters['size'] = $this->size;
        }

        if ($this->missing) {
            $parameters['missing'] = $this->missing;
        }

        if ($this->order) {
            $parameters['order'] = array_map(fn (TermsSort $sort) => $sort->toArray(), $this->order);
        }

        $aggregation = [
            'multi_terms' => $parameters,
        ];

        if (! $this->aggregations->isEmpty()) {
            $aggregation['aggs'] = $this->aggregations->toArray();
        }

        return $aggregation;
    }
}
