<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\IdsQuery;

class IdsQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = IdsQuery::create(['1', '2', '3']);

        self::assertInstanceOf(IdsQuery::class, $query);
    }

    public function testWithSingleId(): void
    {
        $query = new IdsQuery(['doc_1']);

        self::assertEquals([
            'ids' => [
                'values' => ['doc_1'],
            ],
        ], $query->toArray());
    }

    public function testWithMultipleIds(): void
    {
        $query = new IdsQuery(['doc_1', 'doc_2', 'doc_3']);

        self::assertEquals([
            'ids' => [
                'values' => ['doc_1', 'doc_2', 'doc_3'],
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = IdsQuery::create(['user_1', 'user_2']);

        self::assertEquals([
            'ids' => [
                'values' => ['user_1', 'user_2'],
            ],
        ], $query->toArray());
    }

    public function testWithNumericIds(): void
    {
        $query = IdsQuery::create([1, 2, 3]);

        self::assertEquals([
            'ids' => [
                'values' => [1, 2, 3],
            ],
        ], $query->toArray());
    }

    public function testWithMixedIds(): void
    {
        $query = IdsQuery::create(['string_id', 123, 'another_id']);

        self::assertEquals([
            'ids' => [
                'values' => ['string_id', 123, 'another_id'],
            ],
        ], $query->toArray());
    }

    public function testWithEmptyArray(): void
    {
        $query = IdsQuery::create([]);

        self::assertEquals([
            'ids' => [
                'values' => [],
            ],
        ], $query->toArray());
    }
}