<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Aggregations;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Aggregations\AvgAggregation;
use Spatie\ElasticsearchQueryBuilder\Aggregations\HistogramAggregation;

class HistogramAggregationTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $aggregation = HistogramAggregation::create('price_histogram', 'price', 10.0);

        self::assertInstanceOf(HistogramAggregation::class, $aggregation);
    }

    public function testDefaultSetup(): void
    {
        $aggregation = new HistogramAggregation('price_histogram', 'price', 10.0);

        self::assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 10.0,
            ],
        ], $aggregation->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $aggregation = HistogramAggregation::create('score_histogram', 'score', 5.0);

        self::assertEquals([
            'histogram' => [
                'field' => 'score',
                'interval' => 5.0,
            ],
        ], $aggregation->toArray());
    }

    public function testWithMinDocCount(): void
    {
        $aggregation = HistogramAggregation::create('price_histogram', 'price', 10.0)
            ->minDocCount(1);

        self::assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 10.0,
                'min_doc_count' => 1,
            ],
        ], $aggregation->toArray());
    }

    public function testWithExtendedBounds(): void
    {
        $aggregation = HistogramAggregation::create('price_histogram', 'price', 10.0)
            ->extendedBounds(0.0, 100.0);

        self::assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 10.0,
                'extended_bounds' => [
                    'min' => 0.0,
                    'max' => 100.0,
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testWithOffset(): void
    {
        $aggregation = HistogramAggregation::create('price_histogram', 'price', 10.0)
            ->offset(2.5);

        self::assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 10.0,
                'offset' => 2.5,
            ],
        ], $aggregation->toArray());
    }

    public function testWithMissing(): void
    {
        $aggregation = HistogramAggregation::create('price_histogram', 'price', 10.0)
            ->missing('0');

        self::assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 10.0,
                'missing' => '0',
            ],
        ], $aggregation->toArray());
    }

    public function testWithSubAggregation(): void
    {
        $subAggregation = AvgAggregation::create('avg_price', 'price');
        $aggregation = HistogramAggregation::create('price_histogram', 'price', 10.0)
            ->aggregation($subAggregation);

        self::assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 10.0,
            ],
            'aggs' => [
                'avg_price' => [
                    'avg' => [
                        'field' => 'price',
                    ],
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testFullSetup(): void
    {
        $aggregation = HistogramAggregation::create('sales_histogram', 'sales', 100.0)
            ->minDocCount(2)
            ->extendedBounds(0.0, 1000.0)
            ->offset(50.0)
            ->missing('0');

        self::assertEquals([
            'histogram' => [
                'field' => 'sales',
                'interval' => 100.0,
                'min_doc_count' => 2,
                'extended_bounds' => [
                    'min' => 0.0,
                    'max' => 1000.0,
                ],
                'offset' => 50.0,
                'missing' => '0',
            ],
        ], $aggregation->toArray());
    }

    public function testFluentInterface(): void
    {
        $aggregation = HistogramAggregation::create('score_histogram', 'score', 1.0)
            ->minDocCount(0)
            ->offset(0.5);

        self::assertInstanceOf(HistogramAggregation::class, $aggregation);
        self::assertEquals([
            'histogram' => [
                'field' => 'score',
                'interval' => 1.0,
                'min_doc_count' => 0,
                'offset' => 0.5,
            ],
        ], $aggregation->toArray());
    }

    public function testWithMeta(): void
    {
        $aggregation = HistogramAggregation::create('price_histogram', 'price', 10.0)
            ->meta(['description' => 'Price distribution histogram']);

        self::assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 10.0,
            ],
            'meta' => [
                'description' => 'Price distribution histogram',
            ],
        ], $aggregation->toArray());
    }

    public function testWithIntegerInterval(): void
    {
        $aggregation = HistogramAggregation::create('age_histogram', 'age', 5);

        self::assertEquals([
            'histogram' => [
                'field' => 'age',
                'interval' => 5,
            ],
        ], $aggregation->toArray());
    }

    public function testWithNegativeValues(): void
    {
        $aggregation = HistogramAggregation::create('temp_histogram', 'temperature', 2.0)
            ->extendedBounds(-10.0, 40.0)
            ->offset(-1.0);

        self::assertEquals([
            'histogram' => [
                'field' => 'temperature',
                'interval' => 2.0,
                'extended_bounds' => [
                    'min' => -10.0,
                    'max' => 40.0,
                ],
                'offset' => -1.0,
            ],
        ], $aggregation->toArray());
    }

    public function testWithHardBounds(): void
    {
        $aggregation = HistogramAggregation::create('price_histogram', 'price', 10.0)
            ->hardBounds(0.0, 100.0);

        self::assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 10.0,
                'hard_bounds' => [
                    'min' => 0.0,
                    'max' => 100.0,
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testWithAllParameters(): void
    {
        $aggregation = HistogramAggregation::create('comprehensive_histogram', 'value', 5.0)
            ->minDocCount(2)
            ->extendedBounds(-5.0, 50.0)
            ->hardBounds(0.0, 40.0)
            ->offset(1.0)
            ->missing(0);

        self::assertEquals([
            'histogram' => [
                'field' => 'value',
                'interval' => 5.0,
                'min_doc_count' => 2,
                'extended_bounds' => [
                    'min' => -5.0,
                    'max' => 50.0,
                ],
                'hard_bounds' => [
                    'min' => 0.0,
                    'max' => 40.0,
                ],
                'offset' => 1.0,
                'missing' => 0,
            ],
        ], $aggregation->toArray());
    }
}
