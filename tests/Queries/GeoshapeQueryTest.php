<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Enums\GeoshapeType;
use Spatie\ElasticsearchQueryBuilder\Enums\SpatialRelation;
use Spatie\ElasticsearchQueryBuilder\Queries\GeoshapeQuery;

final class GeoshapeQueryTest extends TestCase
{
    public function testToArrayBuildsCorrectGeoshapeQuery(): void
    {
        $query = GeoshapeQuery::create(
            'location',
            GeoshapeType::POLYGON,
            [
                [
                    [102.0, 2.0],
                    [103.0, 2.0],
                    [103.0, 3.0],
                    [102.0, 3.0],
                    [102.0, 2.0],
                ],
            ],
            SpatialRelation::WITHIN,
        );

        $expected = [
            'geo_shape' => [
                'location' => [
                    'shape' => [
                        'type' => 'polygon',
                        'coordinates' => [
                            [
                                [102.0, 2.0],
                                [103.0, 2.0],
                                [103.0, 3.0],
                                [102.0, 3.0],
                                [102.0, 2.0],
                            ],
                        ],
                    ],
                    'relation' => 'within',
                ],
            ],
        ];

        self::assertEquals($expected, $query->toArray());
    }
}
