<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Enums\GeoshapeType;
use Spatie\ElasticsearchQueryBuilder\Enums\SpatialRelation;
use Spatie\ElasticsearchQueryBuilder\Queries\GeoshapeQuery;

final class GeoshapeQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = GeoshapeQuery::create('location', GeoshapeType::POINT, [1.0, 2.0]);

        self::assertInstanceOf(GeoshapeQuery::class, $query);
    }

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
                    'relation' => 'intersects',
                ],
            ],
        ];

        self::assertEquals($expected, $query->toArray());
    }

    public function testToArrayBuildsCorrectGeoshapeQueryWithMultiplePolygons(): void
    {
        $query = GeoshapeQuery::create(
            'location',
            GeoshapeType::MULTI_POLYGON,
            [
                [
                    [
                        [102.0, 2.0],
                        [103.0, 2.0],
                        [103.0, 3.0],
                        [102.0, 3.0],
                        [102.0, 2.0],
                    ],
                ],
                [
                    [
                        [100.0, 0.0],
                        [101.0, 0.0],
                        [101.0, 1.0],
                        [100.0, 1.0],
                        [100.0, 0.0],
                    ],
                ],
            ],
            SpatialRelation::WITHIN,
        );

        $expected = [
            'geo_shape' => [
                'location' => [
                    'shape' => [
                        'type' => 'multipolygon',
                        'coordinates' => [
                            [
                                [
                                    [102.0, 2.0],
                                    [103.0, 2.0],
                                    [103.0, 3.0],
                                    [102.0, 3.0],
                                    [102.0, 2.0],
                                ],
                            ],
                            [
                                [
                                    [100.0, 0.0],
                                    [101.0, 0.0],
                                    [101.0, 1.0],
                                    [100.0, 1.0],
                                    [100.0, 0.0],
                                ],
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
