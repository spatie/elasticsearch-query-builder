<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Sorts;

use Spatie\ElasticsearchQueryBuilder\Queries\Query;
use Spatie\ElasticsearchQueryBuilder\Sorts\NestedSort;
use PHPUnit\Framework\TestCase;

class NestedSortTest extends TestCase
{
    public function testToArrayBuildsCorrectNestedSort(): void
    {
        $nestedSort = NestedSort::create('path', 'path.field', NestedSort::DESC);

        $this->assertEquals(
            [
                'path.field' => [
                    'order' => NestedSort::DESC,
                    'nested' => [
                        'path' => 'path',
                    ]
                ]
            ],
            $nestedSort->toArray()
        );
    }

    public function testToArrayBuildsCorrectNestedSortWithFilter(): void
    {
        $filterMock = $this->createMock(Query::class);
        $filterMock->method('toArray')
            ->willReturn(['query']);

        $nestedSort = NestedSort::create(
            'path',
            'path.field',
            NestedSort::DESC
        )->filter($filterMock);

        $this->assertEquals(
            [
                'path.field' => [
                    'order' => NestedSort::DESC,
                    'nested' => [
                        'path' => 'path',
                        'filter' => ['query'],
                    ]
                ]
            ],
            $nestedSort->toArray()
        );
    }

    public function testToArrayBuildsCorrectNestedSortWithMode(): void
    {
        $nestedSort = NestedSort::create(
            'path',
            'path.field',
            NestedSort::DESC,
        )->mode('avg');

        $this->assertEquals(
            [
                'path.field' => [
                    'order' => NestedSort::DESC,
                    'mode' => 'avg',
                    'nested' => [
                        'path' => 'path',
                    ]
                ]
            ],
            $nestedSort->toArray()
        );
    }

    public function testToArrayBuildsCorrectNestedSortWithNested(): void
    {
        $nestedMock = $this->createMock(NestedSort::class);
        $nestedMock
            ->method('toArray')
            ->willReturn(['nested']);

        $nestedSort = NestedSort::create(
            'path',
            'path.field',
            NestedSort::DESC
        )->nested($nestedMock);

        $this->assertEquals(
            [
                'path.field' => [
                    'order' => NestedSort::DESC,
                    'nested' => [
                        'path' => 'path',
                        'nested' => ['nested'],
                    ]
                ]
            ],
            $nestedSort->toArray()
        );
    }

    public function testToArrayBuildsCorrectNestedSortWithMissing(): void
    {
        $nestedMock = $this->createMock(NestedSort::class);
        $nestedMock
            ->method('toArray')
            ->willReturn(['nested']);

        $nestedSort = NestedSort::create(
            'path',
            'path.field',
            NestedSort::DESC
        )->missing('_last');

        $this->assertEquals(
            [
                'path.field' => [
                    'order' => NestedSort::DESC,
                    'missing' => '_last',
                    'nested' => [
                        'path' => 'path',
                    ]
                ]
            ],
            $nestedSort->toArray()
        );
    }

    public function testToArrayBuildsCorrectNestedSortWithUnmappedType(): void
    {
        $nestedMock = $this->createMock(NestedSort::class);
        $nestedMock
            ->method('toArray')
            ->willReturn(['nested']);

        $nestedSort = NestedSort::create(
            'path',
            'path.field',
            NestedSort::DESC
        )->unmappedType('long');

        $this->assertEquals(
            [
                'path.field' => [
                    'order' => NestedSort::DESC,
                    'unmapped_type' => 'long',
                    'nested' => [
                        'path' => 'path',
                    ]
                ]
            ],
            $nestedSort->toArray()
        );
    }

    public function testToArrayBuildsCorrectNestedSortWithMaxChildren(): void
    {
        $nestedMock = $this->createMock(NestedSort::class);
        $nestedMock
            ->method('toArray')
            ->willReturn(['nested']);

        $nestedSort = NestedSort::create(
            'path',
            'path.field',
            NestedSort::DESC
        )->maxChildren(123);

        $this->assertEquals(
            [
                'path.field' => [
                    'order' => NestedSort::DESC,
                    'nested' => [
                        'path' => 'path',
                        'max_children' => 123,
                    ]
                ]
            ],
            $nestedSort->toArray()
        );
    }
}
