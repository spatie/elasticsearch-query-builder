<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery;

class RangeQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = RangeQuery::create('age');

        self::assertInstanceOf(RangeQuery::class, $query);
    }

    public function testWithNoConditions(): void
    {
        $query = new RangeQuery('age');

        self::assertEquals([
            'range' => [
                'age' => [],
            ],
        ], $query->toArray());
    }

    public function testWithGte(): void
    {
        $query = RangeQuery::create('age')->gte(18);

        self::assertEquals([
            'range' => [
                'age' => [
                    'gte' => 18,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithLte(): void
    {
        $query = RangeQuery::create('age')->lte(65);

        self::assertEquals([
            'range' => [
                'age' => [
                    'lte' => 65,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithGt(): void
    {
        $query = RangeQuery::create('score')->gt(50.5);

        self::assertEquals([
            'range' => [
                'score' => [
                    'gt' => 50.5,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithLt(): void
    {
        $query = RangeQuery::create('price')->lt(100.0);

        self::assertEquals([
            'range' => [
                'price' => [
                    'lt' => 100.0,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithMultipleConditions(): void
    {
        $query = RangeQuery::create('age')
            ->gte(18)
            ->lte(65);

        self::assertEquals([
            'range' => [
                'age' => [
                    'gte' => 18,
                    'lte' => 65,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithAllConditions(): void
    {
        $query = RangeQuery::create('score')
            ->gte(10)
            ->gt(5)
            ->lte(100)
            ->lt(95);

        self::assertEquals([
            'range' => [
                'score' => [
                    'lt' => 95,
                    'lte' => 100,
                    'gt' => 5,
                    'gte' => 10,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithStringValues(): void
    {
        $query = RangeQuery::create('date')
            ->gte('2023-01-01')
            ->lte('2023-12-31');

        self::assertEquals([
            'range' => [
                'date' => [
                    'gte' => '2023-01-01',
                    'lte' => '2023-12-31',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithFloatValues(): void
    {
        $query = RangeQuery::create('rating')
            ->gte(3.5)
            ->lt(4.8);

        self::assertEquals([
            'range' => [
                'rating' => [
                    'gte' => 3.5,
                    'lt' => 4.8,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithNullValues(): void
    {
        $query = RangeQuery::create('value')
            ->gte(null)
            ->lte(100);

        // When setting null, the condition should not be included
        self::assertEquals([
            'range' => [
                'value' => [
                    'lte' => 100,
                ],
            ],
        ], $query->toArray());
    }

    public function testFluentInterface(): void
    {
        $query = RangeQuery::create('price')
            ->gte(10)
            ->lte(50);

        self::assertInstanceOf(RangeQuery::class, $query);
        self::assertEquals([
            'range' => [
                'price' => [
                    'gte' => 10,
                    'lte' => 50,
                ],
            ],
        ], $query->toArray());
    }

    public function testOverwritingConditions(): void
    {
        $query = RangeQuery::create('score')
            ->gte(10)
            ->gte(20); // Should overwrite the previous value

        self::assertEquals([
            'range' => [
                'score' => [
                    'gte' => 20,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithNestedField(): void
    {
        $query = RangeQuery::create('user.age')->gte(21);

        self::assertEquals([
            'range' => [
                'user.age' => [
                    'gte' => 21,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithZeroValues(): void
    {
        $query = RangeQuery::create('count')
            ->gte(0)
            ->lt(1000);

        self::assertEquals([
            'range' => [
                'count' => [
                    'gte' => 0,
                    'lt' => 1000,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithNegativeValues(): void
    {
        $query = RangeQuery::create('temperature')
            ->gte(-10)
            ->lte(40);

        self::assertEquals([
            'range' => [
                'temperature' => [
                    'gte' => -10,
                    'lte' => 40,
                ],
            ],
        ], $query->toArray());
    }

    public function testSettingNullRemovesCondition(): void
    {
        $query = RangeQuery::create('value')
            ->gte(10)
            ->lte(100)
            ->gte(null); // This should remove the gte condition

        self::assertEquals([
            'range' => [
                'value' => [
                    'lte' => 100,
                ],
            ],
        ], $query->toArray());
    }
}