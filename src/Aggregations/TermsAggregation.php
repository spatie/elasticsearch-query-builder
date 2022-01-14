<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithMissing;

class TermsAggregation extends Aggregation
{
    use WithMissing;
    use WithAggregations;

    protected string $field;

    protected ?int $size = null;

    protected ?array $order = null;

    private ?int $min_doc_count = null;

    private ?int $shard_size = null;

    public static function create(string $name, string $field): self
    {
        return new self($name, $field);
    }

    public function __construct(string $name, string $field)
    {
        $this->name = $name;
        $this->field = $field;
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

    public function minDocCount(int $min_doc_count): self
    {
        $this->min_doc_count = $min_doc_count;

        return $this;
    }

    public function shardSize(int $shard_size): self
    {
        $this->shard_size = $shard_size;

        return $this;
    }

    public function payload(): array
    {
        $parameters = [
            'field' => $this->field,
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

        if ($this->min_doc_count) {
            $parameters['min_doc_count'] = $this->min_doc_count;
        }

        if ($this->shard_size) {
            $parameters['shard_size'] = $this->shard_size;
        }

        $aggregation = [
            'terms' => $parameters,
        ];

        if (! $this->aggregations->isEmpty()) {
            $aggregation['aggs'] = $this->aggregations->toArray();
        }

        return $aggregation;
    }
}
