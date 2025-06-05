<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Spatie\ElasticsearchQueryBuilder\Builder;

class CollapseTest extends TestCase
{

    private Builder $builder;

    protected function setUp(): void
    {
        $this->builder = new Builder();
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
