<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

class BucketScriptAggregation extends Aggregation
{
    use WithAggregations;

    protected array $buckets_path;

    protected string $script;

    public static function create(
        string $name,
        array  $buckets_path,
        string $script,
        Aggregation ...$aggregations
    ): self {
        return new self($name, $buckets_path, $script, ...$aggregations);
    }

    public function __construct(
        string $name,
        array  $buckets_path,
        string $script,
        Aggregation ...$aggregations
    ) {
        $this->name = $name;
        $this->buckets_path = $buckets_path;
        $this->script = $script;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    public function payload(): array
    {
        $aggregation = [
            'bucket_script' => [
                'buckets_path' => $this->buckets_path,
                'script' => $this->script,
            ],
        ];

        if (! $this->aggregations->isEmpty()) {
            $aggregation['aggs'] = $this->aggregations->toArray();
        }

        return $aggregation;
    }
}
