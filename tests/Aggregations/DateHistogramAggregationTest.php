<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Aggregations;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Aggregations\DateHistogramAggregation;
use Spatie\ElasticsearchQueryBuilder\Aggregations\TermsAggregation;

class DateHistogramAggregationTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $aggregation = DateHistogramAggregation::create('test_name', 'test_field', '1h');

        self::assertInstanceOf(DateHistogramAggregation::class, $aggregation);
    }

    public function testDefaultSetup(): void
    {
        $aggregation = new DateHistogramAggregation('test_name', 'test_field', '1h');

        self::assertEquals([
            'date_histogram' => [
                'field' => 'test_field',
                'calendar_interval' => '1h',
            ],
        ], $aggregation->toArray());
    }

    public function testDefaultSetupWithAdditionalAggregation(): void
    {
        $aggregation = (new DateHistogramAggregation('test_name', 'test_field', '1h'))->aggregation(new TermsAggregation('test_agg_name_1', 'test_agg_field_1'));

        self::assertEquals([
            'date_histogram' => [
                'field' => 'test_field',
                'calendar_interval' => '1h',
            ],
            'aggs' => [
                'test_agg_name_1' => [
                    'terms' => [
                        'field' => 'test_agg_field_1',
                    ],
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testFullSetup(): void
    {
        $aggregation = new DateHistogramAggregation(
            'test_name',
            'test_field',
            '1h',
            new TermsAggregation('test_agg_name_1', 'test_agg_field_1'),
            new TermsAggregation('test_agg_name_2', 'test_agg_field_2')
        );

        self::assertEquals([
            'date_histogram' => [
                'field' => 'test_field',
                'calendar_interval' => '1h',
            ],
            'aggs' => [
                'test_agg_name_1' => [
                    'terms' => [
                        'field' => 'test_agg_field_1',
                    ],
                ],
                'test_agg_name_2' => [
                    'terms' => [
                        'field' => 'test_agg_field_2',
                    ],
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testDefaultSetupWithStaticCreateFunction(): void
    {
        $aggregation = DateHistogramAggregation::create('test_name', 'test_field', '1h');

        self::assertEquals([
            'date_histogram' => [
                'field' => 'test_field',
                'calendar_interval' => '1h',
            ],
        ], $aggregation->toArray());
    }

    public function testDefaultSetupWithAdditionalAggregationWithStaticCreateFunction(): void
    {
        $aggregation = DateHistogramAggregation::create('test_name', 'test_field', '1h')->aggregation(TermsAggregation::create('test_agg_name_1', 'test_agg_field_1'));

        self::assertEquals([
            'date_histogram' => [
                'field' => 'test_field',
                'calendar_interval' => '1h',
            ],
            'aggs' => [
                'test_agg_name_1' => [
                    'terms' => [
                        'field' => 'test_agg_field_1',
                    ],
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testFullSetupWithStaticCreateFunction(): void
    {
        $aggregation = DateHistogramAggregation::create(
            'test_name',
            'test_field',
            '1h',
            TermsAggregation::create('test_agg_name_1', 'test_agg_field_1'),
            TermsAggregation::create('test_agg_name_2', 'test_agg_field_2')
        );

        self::assertEquals([
            'date_histogram' => [
                'field' => 'test_field',
                'calendar_interval' => '1h',
            ],
            'aggs' => [
                'test_agg_name_1' => [
                    'terms' => [
                        'field' => 'test_agg_field_1',
                    ],
                ],
                'test_agg_name_2' => [
                    'terms' => [
                        'field' => 'test_agg_field_2',
                    ],
                ],
            ],
        ], $aggregation->toArray());
    }
}
