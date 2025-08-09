<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\MatchNoneQuery;

class MatchNoneQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = MatchNoneQuery::create();

        self::assertInstanceOf(MatchNoneQuery::class, $query);
    }

    public function testDefaultSetup(): void
    {
        $query = new MatchNoneQuery();

        self::assertEquals([
            'match_none' => new \stdClass(),
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = MatchNoneQuery::create();

        self::assertEquals([
            'match_none' => new \stdClass(),
        ], $query->toArray());
    }

    public function testMultipleInstancesAreIndependent(): void
    {
        $query1 = MatchNoneQuery::create();
        $query2 = MatchNoneQuery::create();

        self::assertNotSame($query1, $query2);
        self::assertEquals($query1->toArray(), $query2->toArray());
    }

    public function testConsistentOutput(): void
    {
        $query = new MatchNoneQuery();
        $expected = [
            'match_none' => new \stdClass(),
        ];

        // Test multiple calls to ensure consistency
        self::assertEquals($expected, $query->toArray());
        self::assertEquals($expected, $query->toArray());
        self::assertEquals($expected, $query->toArray());
    }

    public function testEmptyObjectStructure(): void
    {
        $query = MatchNoneQuery::create();
        $result = $query->toArray();

        self::assertArrayHasKey('match_none', $result);
        self::assertInstanceOf(\stdClass::class, $result['match_none']);
        self::assertEquals(new \stdClass(), $result['match_none']);
    }
}
