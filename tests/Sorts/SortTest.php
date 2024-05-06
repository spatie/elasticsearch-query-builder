<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Sorts;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class SortTest extends TestCase
{
    private Sort $sort;

    protected function setUp(): void
    {
        $this->sort = new Sort('sortField', Sort::ASC);
    }

    public function testToArrayBuildsCorrectSort(): void
    {
        self::assertEquals(
            [
                'sortField' => [
                    'order' => Sort::ASC,
                ]
            ],
            $this->sort->toArray()
        );
    }

    public function testToArrayBuildsCorrectSortWithMissing(): void
    {
        self::assertEquals(
            [
                'sortField' => [
                    'order' => Sort::ASC,
                    'missing' => '_last'
                ]
            ],
            $this->sort->missing('_last')->toArray()
        );
    }

    public function testToArrayBuildsCorrectSortWithUnmappedType(): void
    {
        self::assertEquals(
            [
                'sortField' => [
                    'order' => Sort::ASC,
                    'unmapped_type' => 'long'
                ]
            ],
            $this->sort->unmappedType('long')->toArray()
        );
    }

    public function testToArrayBuildsCorrectSortWithMode(): void
    {
        self::assertEquals(
            [
                'sortField' => [
                    'order' => Sort::ASC,
                    'mode' => 'avg'
                ]
            ],
            $this->sort->mode('avg')->toArray()
        );
    }
}
