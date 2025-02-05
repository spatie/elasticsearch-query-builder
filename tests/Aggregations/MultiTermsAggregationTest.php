<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Aggregations;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Spatie\ElasticsearchQueryBuilder\Aggregations\MultiTermsAggregation;
use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Builder;

class MultiTermsAggregationTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = ClientBuilder::create()->build();
    }

    public function testMultiTermsAggregation(): void
    {
        $builder = new Builder($this->client);

        $aggregation = MultiTermsAggregation::create('agg_name', ['field1', 'field2']);

        $expectedAggArray = [
            'multi_terms' => [
                'terms' => [
                    ['field' => 'field1'],
                    ['field' => 'field2']
                ],
            ],
        ];
        $this->assertEquals($expectedAggArray, $aggregation->toArray());

        $builder->addAggregation($aggregation);

        $expectedBuilderPayload = [
            'aggs' => [
                'agg_name' => $expectedAggArray,
            ]
        ];

        $this->assertEquals($expectedBuilderPayload, $builder->getPayload());
    }
}
