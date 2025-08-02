<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Aggregations;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Aggregations\PercentilesAggregation;

class PercentilesAggregationTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $aggregation = PercentilesAggregation::create('test_name', 'test_field', [50]);

        self::assertInstanceOf(PercentilesAggregation::class, $aggregation);
    }

    public function testDefaultSetup(): void
    {
        $aggregation = new PercentilesAggregation('test_name', 'test_field', [1, 5, 25, 50, 75, 95, 99]);

        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [1, 5, 25, 50, 75, 95, 99],
            ],
        ], $aggregation->toArray());
    }

    public function testDefaultSetupWithStaticCreateFunction(): void
    {
        $aggregation = PercentilesAggregation::create('test_name', 'test_field', [1, 5, 25, 50, 75, 95, 99]);

        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [1, 5, 25, 50, 75, 95, 99],
            ],
        ], $aggregation->toArray());
    }

    public function testWithCustomPercents(): void
    {
        $aggregation = PercentilesAggregation::create('test_name', 'test_field', [10, 50, 90]);

        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [10, 50, 90],
            ],
        ], $aggregation->toArray());
    }

    public function testWithCompression(): void
    {
        $aggregation = PercentilesAggregation::create('test_name', 'test_field', [50])
            ->compression(200);

        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [50],
                'tdigest' => [
                    'compression' => 200,
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testWithMethod(): void
    {
        $aggregation = PercentilesAggregation::create('test_name', 'test_field', [50])
            ->method('hdr');

        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [50],
                'method' => 'hdr',
            ],
        ], $aggregation->toArray());
    }

    public function testWithMissing(): void
    {
        $aggregation = PercentilesAggregation::create('test_name', 'test_field', [50])
            ->missing('10');

        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [50],
                'missing' => '10',
            ],
        ], $aggregation->toArray());
    }

    public function testFullSetup(): void
    {
        $aggregation = PercentilesAggregation::create('test_name', 'test_field', [25, 50, 75])
            ->compression(1000)
            ->method('tdigest')
            ->missing('10');

        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [25, 50, 75],
                'tdigest' => [
                    'compression' => 1000,
                ],
                'method' => 'tdigest',
                'missing' => '10',
            ],
        ], $aggregation->toArray());
    }

    public function testFluentInterface(): void
    {
        $aggregation = PercentilesAggregation::create('test_name', 'test_field', [50, 90, 95])
            ->compression(500);

        self::assertInstanceOf(PercentilesAggregation::class, $aggregation);
        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [50, 90, 95],
                'tdigest' => [
                    'compression' => 500,
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testWithMeta(): void
    {
        $aggregation = PercentilesAggregation::create('test_name', 'test_field', [50])
            ->meta(['description' => 'Response time percentiles']);

        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [50],
            ],
            'meta' => [
                'description' => 'Response time percentiles',
            ],
        ], $aggregation->toArray());
    }
}
