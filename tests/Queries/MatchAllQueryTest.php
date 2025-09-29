<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\MatchAllQuery;

class MatchAllQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = MatchAllQuery::create();

        self::assertInstanceOf(MatchAllQuery::class, $query);
    }

    public function testDefaultSetup(): void
    {
        $query = new MatchAllQuery();

        self::assertEquals([
            'match_all' => new \stdClass(),
        ], $query->toArray());
    }

    public function testWithBoost(): void
    {
        $query = new MatchAllQuery(2.5);

        self::assertEquals([
            'match_all' => [
                'boost' => 2.5,
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = MatchAllQuery::create(1.5);

        self::assertEquals([
            'match_all' => [
                'boost' => 1.5,
            ],
        ], $query->toArray());
    }

    public function testCreateWithoutBoost(): void
    {
        $query = MatchAllQuery::create();

        self::assertEquals([
            'match_all' => new \stdClass(),
        ], $query->toArray());
    }

    public function testCreateWithZeroBoost(): void
    {
        $query = MatchAllQuery::create(0.0);

        self::assertEquals([
            'match_all' => [
                'boost' => 0.0,
            ],
        ], $query->toArray());
    }
}
