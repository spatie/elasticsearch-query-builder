<?php

namespace Spatie\ElasticsearchQueryBuilder;

use Spatie\ElasticsearchQueryBuilder\Aggregations\Aggregation;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery\InnerHits;
use Spatie\ElasticsearchQueryBuilder\Queries\Query;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sorting;

class Builder
{
    protected ?BoolQuery $query = null;

    protected ?AggregationCollection $aggregations = null;

    protected ?SortCollection $sorts = null;

    protected ?string $searchIndex = null;

    protected ?int $size = null;

    protected ?int $from = null;

    protected ?float $minScore = null;

    protected ?array $searchAfter = null;

    protected ?array $fields = null;

    protected bool $withAggregations = true;

    protected bool $trackTotalHits = false;

    protected ?array $highlight = null;

    protected ?BoolQuery $postFilterQuery = null;

    protected ?array $collapse = null;

    public function __construct()
    {
    }

    public function addQuery(Query $query, string $boolType = 'must'): static
    {
        if (! $this->query) {
            $this->query = new BoolQuery();
        }

        $this->query->add($query, $boolType);

        return $this;
    }

    public function addAggregation(Aggregation $aggregation): static
    {
        if (! $this->aggregations) {
            $this->aggregations = new AggregationCollection();
        }

        $this->aggregations->add($aggregation);

        return $this;
    }

    public function addSort(Sorting $sort): static
    {
        if (! $this->sorts) {
            $this->sorts = new SortCollection();
        }

        $this->sorts->add($sort);

        return $this;
    }

    public function params(): array
    {
        $payload = $this->getPayload();

        $params = [
            'body' => $payload,
        ];

        if ($this->searchIndex) {
            $params['index'] = $this->searchIndex;
        }

        if ($this->size !== null) {
            $params['size'] = $this->size;
        }

        if ($this->from !== null) {
            $params['from'] = $this->from;
        }

        if ($this->trackTotalHits) {
            $params['track_total_hits'] = true;
        }

        return $params;
    }

    public function index(string $searchIndex): static
    {
        $this->searchIndex = $searchIndex;

        return $this;
    }

    public function getIndex(): ?string
    {
        return $this->searchIndex;
    }

    public function trackTotalHits(bool $value = true): static
    {
        $this->trackTotalHits = $value;

        return $this;
    }

    public function size(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function from(int $from): static
    {
        $this->from = $from;

        return $this;
    }

    public function minScore(float $minScore): static
    {
        $this->minScore = $minScore;

        return $this;
    }

    public function searchAfter(?array $searchAfter): static
    {
        $this->searchAfter = $searchAfter;

        return $this;
    }

    public function fields(array $fields): static
    {
        $this->fields = array_merge($this->fields ?? [], $fields);

        return $this;
    }

    public function withoutAggregations(): static
    {
        $this->withAggregations = false;

        return $this;
    }

    public function highlight(array $highlight): static
    {
        $this->highlight = $highlight;

        return $this;
    }

    public function addPostFilterQuery(Query $query, string $boolType = 'must'): static
    {
        if (! $this->postFilterQuery) {
            $this->postFilterQuery = new BoolQuery();
        }

        $this->postFilterQuery->add($query, $boolType);

        return $this;
    }

    public function collapse(string $field, array|InnerHits|null $innerHits = null, ?int $maxConcurrentGroupRequests = null): static
    {
        $this->collapse = ['field' => $field];

        if ($innerHits) {
            if ($innerHits instanceof InnerHits) {
                $innerHits = $innerHits->toArray();
            }
            $this->collapse['inner_hits'] = $innerHits;
        }

        if ($maxConcurrentGroupRequests !== null) {
            $this->collapse['max_concurrent_group_searches'] = $maxConcurrentGroupRequests;
        }

        return $this;
    }

    public function getPayload(): array
    {
        $payload = [];

        if ($this->from !== null) {
            $payload['from'] = $this->from;
        }

        if ($this->size !== null) {
            $payload['size'] = $this->size;
        }

        if ($this->minScore !== null) {
            $payload['min_score'] = $this->minScore;
        }

        if ($this->query) {
            $payload['query'] = $this->query->toArray();
        }

        if ($this->withAggregations && $this->aggregations) {
            $payload['aggs'] = $this->aggregations->toArray();
        }

        if ($this->sorts) {
            $payload['sort'] = $this->sorts->toArray();
        }

        if ($this->fields) {
            $payload['_source'] = $this->fields;
        }

        if ($this->searchAfter) {
            $payload['search_after'] = $this->searchAfter;
        }

        if ($this->highlight) {
            $payload['highlight'] = $this->highlight;
        }

        if ($this->postFilterQuery) {
            $payload['post_filter'] = $this->postFilterQuery->toArray();
        }

        if ($this->collapse) {
            $payload['collapse'] = $this->collapse;
        }

        return $payload;
    }
}
