<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Aggregations;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Aggregations\MultiTermsAggregation;
use Spatie\ElasticsearchQueryBuilder\Builder;

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
}
