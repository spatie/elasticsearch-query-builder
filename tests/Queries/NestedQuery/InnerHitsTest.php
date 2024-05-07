<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries\NestedQuery;

use PHPUnit\Framework\MockObject\MockObject;
use Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery\InnerHits;
use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\SortCollection;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class InnerHitsTest extends TestCase
{
    private SortCollection|MockObject $sortsMock;

    private InnerHits $innerHits;

    protected function setUp(): void
    {
        $this->innerHits = new InnerHits();
    }

    public function testToArrayBuildsCorrectInnerHits(): void
    {
        $this->assertEquals(
            [],
            $this->innerHits->toArray()
        );
    }

    public function testToArrayBuildsCorrectInnerHitsWithName(): void
    {
        $this->assertEquals(
            [
                'name' => 'test',
            ],
            $this->innerHits->name('test')->toArray()
        );
    }

    public function testToArrayBuildsCorrectInnerHitsPayloadWithFrom(): void
    {
        $this->assertEquals(
            [
                'from' => 123,
            ],
            $this->innerHits->from(123)->toArray()
        );
    }

    public function testToArrayBuildsCorrectInnerHitsPayloadWithSize(): void
    {
        $this->assertEquals(
            [
                'size' => 123,
            ],
            $this->innerHits->size(123)->toArray()
        );
    }

    public function testToArrayBuildsCorrectInnerHitsPayloadWithSorts(): void
    {
        $sortMock = $this->createMock(Sort::class);

        $sortMock
            ->method('toArray')
            ->willReturn(['field' => ['order' => Sort::ASC]]);

        $this->assertEquals(
            [
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

        $innerHits = new InnerHits(sorts: $sortsMock);

        $innerHits->addSort($sortMock);
    }
}
