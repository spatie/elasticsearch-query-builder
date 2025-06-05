<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Aggregations;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Aggregations\MultiTermsAggregation;
use Spatie\ElasticsearchQueryBuilder\Aggregations\SumAggregation;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sorting;
use Spatie\ElasticsearchQueryBuilder\Sorts\TermsSort;

class MultiTermsAggregationTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $aggregation = MultiTermsAggregation::create('agg_name', ['field1', 'field2']);
        self::assertInstanceOf(MultiTermsAggregation::class, $aggregation);
    }

    public function testMultiTermsAggregation(): void
    {
        $aggregation = (new MultiTermsAggregation('agg_name', ['field1', 'field2']));

        self::assertEquals([
            'multi_terms' => [
                'terms' => [
                    ['field' => 'field1'],
                    ['field' => 'field2'],
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testMultiTermsAggregationWithOrder(): void
    {
        $aggregation = (new MultiTermsAggregation('agg_name', ['field1', 'field2']))
            ->addSort(TermsSort::create('_key', 'asc'));

        self::assertEquals([
            'multi_terms' => [
                'terms' => [
                    ['field' => 'field1'],
                    ['field' => 'field2'],
                ],
                'order' => [
                    ['_key' => 'asc'],
                ],
            ],
        ], $aggregation->toArray());
    }

    public function testMultiTermsAggregationOrderedViaCreate(): void
    {
        $multiTermsAgg = MultiTermsAggregation::create(
            'category_subcategory',
            ['category', 'subcategory'],
            10,
            [
                TermsSort::create('total_quantity'),
                TermsSort::create('_key', Sorting::ASC),
            ]
        )->aggregation(
            SumAggregation::create('total_quantity', 'quantity')
        );

        self::assertEquals([
            'multi_terms' => [
                'terms' => [
                    ['field' => 'category'],
                    ['field' => 'subcategory'],
                ],
                'size' => 10,
                'order' => [
                    ['total_quantity' => 'desc'],
                    ['_key' => 'asc'],
                ],
            ],
            'aggs' => [
                'total_quantity' => [
                    'sum' => [
                        'field' => 'quantity',
                    ],
                ],
            ],
        ], $multiTermsAgg->payload());
    }

    public function testMultiTermsAggregationOrderedViaFluent(): void
    {
        $multiTermsAgg = MultiTermsAggregation::create('category_subcategory', ['category', 'subcategory'])
            ->size(10)
            ->addSort(TermsSort::create('total_quantity'))
            ->addSort(TermsSort::create('_key', Sorting::ASC))
            ->aggregation(
                SumAggregation::create('total_quantity', 'quantity')
            );

        self::assertEquals([
            'multi_terms' => [
                'terms' => [
                    ['field' => 'category'],
                    ['field' => 'subcategory'],
                ],
                'size' => 10,
                'order' => [
                    ['total_quantity' => 'desc'],
                    ['_key' => 'asc'],
                ],
            ],
            'aggs' => [
                'total_quantity' => [
                    'sum' => [
                        'field' => 'quantity',
                    ],
                ],
            ],
        ], $multiTermsAgg->payload());
    }
}
