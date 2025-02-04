<?php

namespace Spatie\ElasticsearchQueryBuilder;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;

class MultiBuilder
{
    protected ?array $builders = [];

    public function __construct(protected Client $client)
    {
    }

    public function addBuilder(Builder $builder, ?string $indexName = null): static
    {
        // if we have a name, use the key, else just let is use numeric indices
        $this->builders[] = [
            'index' => $indexName ?? $builder->getIndex(),
            'builder' => $builder,
        ];

        return $this;
    }

    public function getPayload(): array
    {
        $payload = [];
        foreach ($this->builders as $builderInstance) {
            $index = $builderInstance['index'];
            $builder = $builderInstance['builder'];
            $payload[] = $index ? ['index' => $index] : [];
            $payload[] = $builder->getPayload();
        }
        return $payload;
    }

    public function multiSearch(): Elasticsearch|Promise
    {
        $payload = $this->getPayload();

        $params = [
            'body' => $payload,
        ];

        return $this->client->msearch($params);
    }
}
