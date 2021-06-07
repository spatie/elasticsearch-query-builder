<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Aggregation;

trait WithAggregations
{
    protected AggregationCollection $aggregations;

    public function aggregation(Aggregation $aggregation): self
    {
        $this->aggregations->add($aggregation);

        return $this;
    }
}
