<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries\NestedQuery;

use Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery\InnerHits;
use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\SortCollection;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class InnerHitsTest extends TestCase
{
    private InnerHits $innerHits;

    protected function setUp(): void
    {
        $this->innerHits = new InnerHits('test');
    }

    public function testToArrayBuildsCorrectInnerHits(): void
    {
        $this->assertEquals(
            [
                'name' => 'test',
            ],
            $this->innerHits->toArray()
        );
    }

    public function testToArrayBuildsCorrectInnerHitsWithFrom(): void
    {
        $this->assertEquals(
            [
                'name' => 'test',
                'from' => 123,
            ],
            $this->innerHits->from(123)->toArray()
        );
    }

    public function testToArrayBuildsCorrectInnerHitsWithSize(): void
    {
        $this->assertEquals(
            [
                'name' => 'test',
                'size' => 123,
            ],
            $this->innerHits->size(123)->toArray()
        );
    }

    public function testToArrayBuildsCorrectInnerHitsWithSorts(): void
    {
        $sortMock = $this->createMock(Sort::class);

        $sortMock
            ->method('toArray')
            ->willReturn(['field' => ['order' => Sort::ASC]]);

        $this->assertEquals(
            [
                'name' => 'test',
                'sort' => [
                    [
                        'field' => [
                            'order' => Sort::ASC,
                        ]
                    ]
                ]
            ],
            $this->innerHits->addSort($sortMock)->toArray()
        );
    }

    public function testAddSortAddsSortToSorts(): void
    {
        $sortMock = $this->createMock(Sort::class);

        $sortMock
            ->method('toArray')
            ->willReturn(['sort']);

        $sortsMock = $this->createMock(SortCollection::class);

        $sortsMock
            ->expects($this->once())
            ->method('add')
            ->with($sortMock);

        $innerHits = new InnerHits('test', sorts: $sortsMock);

        $innerHits->addSort($sortMock);
    }
}
