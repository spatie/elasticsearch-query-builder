<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithMissing;

class MultiTermsAggregation extends Aggregation
{
    use WithMissing;
    use WithAggregations;

    /** @var string[]  */
    protected array $fields;

    protected ?int $size = null;

    protected ?array $order = null;

    /**
     * @param  string  $name
     * @param  string[]  $fields
     * @return self
     */
    public static function create(string $name, array $fields): self
    {
        return new self($name, $fields);
    }

    public function __construct(string $name, array $fields)
    {
        $this->name = $name;
        $this->fields = $fields;
        $this->aggregations = new AggregationCollection();
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function order(array $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function payload(): array
    {
        $terms = [];
        foreach($this->fields as $field) {
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
            $parameters['order'] = $this->order;
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
