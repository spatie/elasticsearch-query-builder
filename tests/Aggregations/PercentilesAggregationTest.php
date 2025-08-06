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
            ->missing('10');

        self::assertEquals([
            'percentiles' => [
                'field' => 'test_field',
                'percents' => [25, 50, 75],
                'tdigest' => [
                    'compression' => 1000,
                ],
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

    public function testWithKeyed(): void
    {
        $aggregation = PercentilesAggregation::create('response_time_percentiles', 'response_time', [95, 99])
            ->keyed(false);

        self::assertEquals([
            'percentiles' => [
                'field' => 'response_time',
                'percents' => [95, 99],
                'keyed' => false,
            ],
        ], $aggregation->toArray());
    }

    public function testWithTdigest(): void
    {
        $aggregation = PercentilesAggregation::create('load_time_percentiles', 'load_time', [50, 95, 99])
            ->tdigest(200, 'high_accuracy');

        self::assertEquals([
            'percentiles' => [
                'field' => 'load_time',
                'percents' => [50, 95, 99],
                'tdigest' => [
                    'compression' => 200,
                    'execution_hint' => 'high_accuracy',
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testWithHdr(): void
    {
        $aggregation = PercentilesAggregation::create('latency_percentiles', 'latency', [95, 99, 99.9])
            ->hdr(3);

        self::assertEquals([
            'percentiles' => [
                'field' => 'latency',
                'percents' => [95, 99, 99.9],
                'hdr' => [
                    'number_of_significant_value_digits' => 3,
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testWithAllNewFeatures(): void
    {
        $aggregation = PercentilesAggregation::create('comprehensive_percentiles', 'score', [25, 50, 75, 95, 99])
            ->keyed(false)
            ->tdigest(300, 'high_accuracy')
            ->missing(0);

        self::assertEquals([
            'percentiles' => [
                'field' => 'score',
                'percents' => [25, 50, 75, 95, 99],
                'keyed' => false,
                'tdigest' => [
                    'compression' => 300,
                    'execution_hint' => 'high_accuracy',
                ],
                'missing' => 0,
            ],
        ], $aggregation->toArray());
    }
}
