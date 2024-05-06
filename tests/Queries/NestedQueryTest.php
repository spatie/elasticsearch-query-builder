<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery\InnerHits;
use Spatie\ElasticsearchQueryBuilder\Queries\Query;

class NestedQueryTest extends TestCase
{
    private NestedQuery $nestedQuery;

    protected function setUp(): void
    {
        $queryMock = $this->createMock(Query::class);

        $queryMock
            ->method('toArray')
            ->willReturn(['query']);

        $this->nestedQuery = new NestedQuery('path', $queryMock);
    }

    public function testToArrayBuildsCorrectNestedQuery(): void
    {
        $this->assertEquals(
            [
                'nested' => [
                    'path' => 'path',
                    'query' => ['query']
                ]
            ],
            $this->nestedQuery->toArray()
        );
    }

    public function testToArrayBuildsCorrectNestedQueryWithScoreMode(): void
    {
        $this->assertEquals(
            [
                'nested' => [
                    'path' => 'path',
                    'query' => ['query'],
                    'score_mode' => 'min',
                ]
            ],
            $this->nestedQuery->scoreMode('min')->toArray()
        );
    }

    public function testToArrayBuildsCorrectNestedQueryWithIgnoreUnmapped(): void
    {
        $this->assertEquals(
            [
                'nested' => [
                    'path' => 'path',
                    'query' => ['query'],
                    'ignore_unmapped' => true,
                ]
            ],
            $this->nestedQuery->ignoreUnmapped(true)->toArray()
        );
    }

    public function testToArrayBuildsCorrectNestedQueryWithInnerHits(): void
    {
        $innerHitsMock = $this->createMock(InnerHits::class);
        $innerHitsMock
            ->method('getPayload')
            ->willReturn(
                [
                    'size' => 10,
                    'name' => 'test'
                ]
            );

        $this->assertEquals(
            [
                'nested' => [
                    'path' => 'path',
                    'query' => ['query'],
                    'inner_hits' => [
                        'size' => 10,
                        'name' => 'test'
                    ]
                ]
            ],
            $this->nestedQuery->innerHits($innerHitsMock)->toArray()
        );
    }
}
