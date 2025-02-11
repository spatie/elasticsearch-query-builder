<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use Elastic\Elasticsearch\Client;
use Elastic\Transport\TransportBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Spatie\ElasticsearchQueryBuilder\Builder;

class CollapseTest extends TestCase
{

    private Builder $builder;

    private Client $client;

    protected function setUp(): void
    {
        $transport = TransportBuilder::create()
            ->setClient(new \Http\Mock\Client())
            ->build();

        $logger = $this->createStub(LoggerInterface::class);

        $this->client = new Client($transport, $logger);

        $this->builder = new Builder($this->client);
    }

    public function testCollapseIsAddedToPayload()
    {
        $this->builder->collapse(
            'user_id',
            [
                'name' => 'top_comments',
                'size' => 3,
                'sort' => [
                    [
                        'timestamp' => 'desc',
                    ],
                ],
            ],
            10,
        );

        $payload = $this->builder->getPayload();

        $expectedCollapse = [
            'field' => 'user_id',
            'inner_hits' => [
                'name' => 'top_comments',
                'size' => 3,
                'sort' => [
                    [
                        'timestamp' => 'desc',
                    ],
                ],
            ],
            'max_concurrent_group_searches' => 10,
        ];

        $this->assertArrayHasKey('collapse', $payload);
        $this->assertEquals($expectedCollapse, $payload['collapse']);
    }
}
