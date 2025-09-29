<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Aggregations;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Aggregations\AvgAggregation;

class AvgAggregationTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $aggregation = AvgAggregation::create('avg_price', 'price');

        self::assertInstanceOf(AvgAggregation::class, $aggregation);
    }

    public function testDefaultSetup(): void
    {
        $aggregation = new AvgAggregation('avg_price', 'price');

        self::assertEquals([
            'avg' => [
                'field' => 'price',
            ],
        ], $aggregation->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $aggregation = AvgAggregation::create('avg_score', 'score');

        self::assertEquals([
            'avg' => [
                'field' => 'score',
            ],
        ], $aggregation->toArray());
    }

    public function testWithMissing(): void
    {
        $aggregation = AvgAggregation::create('avg_price', 'price')
            ->missing(0);

        self::assertEquals([
            'avg' => [
                'field' => 'price',
                'missing' => '0',
            ],
        ], $aggregation->toArray());
    }

    public function testWithMissingString(): void
    {
        $aggregation = AvgAggregation::create('avg_rating', 'rating')
            ->missing('N/A');

        self::assertEquals([
            'avg' => [
                'field' => 'rating',
                'missing' => 'N/A',
            ],
        ], $aggregation->toArray());
    }

    public function testFluentInterface(): void
    {
        $aggregation = AvgAggregation::create('avg_price', 'price')
            ->missing('10.5');

        self::assertInstanceOf(AvgAggregation::class, $aggregation);
        self::assertEquals([
            'avg' => [
                'field' => 'price',
                'missing' => '10.5',
            ],
        ], $aggregation->toArray());
    }

    public function testWithMeta(): void
    {
        $aggregation = AvgAggregation::create('avg_price', 'price')
            ->meta(['description' => 'Average product price']);

        self::assertEquals([
            'avg' => [
                'field' => 'price',
            ],
            'meta' => [
                'description' => 'Average product price',
            ],
        ], $aggregation->toArray());
    }

    public function testWithMissingAndMeta(): void
    {
        $aggregation = AvgAggregation::create('avg_score', 'score')
            ->missing('0')
            ->meta(['unit' => 'points']);

        self::assertEquals([
            'avg' => [
                'field' => 'score',
                'missing' => '0',
            ],
            'meta' => [
                'unit' => 'points',
            ],
        ], $aggregation->toArray());
    }
}
