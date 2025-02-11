<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Elastic\Elasticsearch\Client;
use Spatie\ElasticsearchQueryBuilder\Builder;

class CollapseTest extends TestCase
{
    public function testCollapseIsAddedToPayload()
    {
        $mockClient = $this->createMock(Client::class);
        $builder = new Builder($mockClient);

        $builder->collapse(
            'user_id',
            [
                'name' => 'top_comments',
                'size' => 3,
                'sort' => [
                    [
                        'timestamp' => 'desc'
                    ]
                ]
            ],
            10,
        );

        $payload = $builder->getPayload();

        $expectedCollapse = [
            'field' => 'user_id',
            'inner_hits' => [
                'name' => 'top_comments',
                'size' => 3,
                'sort' => [
                    [
                        'timestamp' => 'desc'
                    ]
                ],
            ],
            'max_concurrent_group_searches' => 10,
        ];

        $this->assertArrayHasKey('collapse', $payload);
        $this->assertEquals($expectedCollapse, $payload['collapse']);
    }
}
