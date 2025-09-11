<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\TermQuery;

class TermQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = TermQuery::create('status', 'published');

        self::assertInstanceOf(TermQuery::class, $query);
    }

    public function testWithStringValue(): void
    {
        $query = new TermQuery('status', 'published');

        self::assertEquals([
            'term' => [
                'status' => 'published',
            ],
        ], $query->toArray());
    }

    public function testWithIntegerValue(): void
    {
        $query = new TermQuery('age', 25);

        self::assertEquals([
            'term' => [
                'age' => 25,
            ],
        ], $query->toArray());
    }

    public function testWithBooleanValueTrue(): void
    {
        $query = new TermQuery('is_active', true);

        self::assertEquals([
            'term' => [
                'is_active' => true,
            ],
        ], $query->toArray());
    }

    public function testWithBooleanValueFalse(): void
    {
        $query = new TermQuery('is_verified', false);

        self::assertEquals([
            'term' => [
                'is_verified' => false,
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = TermQuery::create('category', 'electronics');

        self::assertEquals([
            'term' => [
                'category' => 'electronics',
            ],
        ], $query->toArray());
    }

    public function testWithZeroValue(): void
    {
        $query = TermQuery::create('count', 0);

        self::assertEquals([
            'term' => [
                'count' => 0,
            ],
        ], $query->toArray());
    }

    public function testWithNegativeValue(): void
    {
        $query = TermQuery::create('balance', -100);

        self::assertEquals([
            'term' => [
                'balance' => -100,
            ],
        ], $query->toArray());
    }

    public function testWithEmptyString(): void
    {
        $query = TermQuery::create('description', '');

        self::assertEquals([
            'term' => [
                'description' => '',
            ],
        ], $query->toArray());
    }

    public function testWithNumericString(): void
    {
        $query = TermQuery::create('id', '12345');

        self::assertEquals([
            'term' => [
                'id' => '12345',
            ],
        ], $query->toArray());
    }

    public function testWithNestedField(): void
    {
        $query = TermQuery::create('user.status', 'active');

        self::assertEquals([
            'term' => [
                'user.status' => 'active',
            ],
        ], $query->toArray());
    }

    public function testWithSpecialCharacters(): void
    {
        $query = TermQuery::create('email', 'test@example.com');

        self::assertEquals([
            'term' => [
                'email' => 'test@example.com',
            ],
        ], $query->toArray());
    }

    public function testWithUnicodeCharacters(): void
    {
        $query = TermQuery::create('name', '测试用户');

        self::assertEquals([
            'term' => [
                'name' => '测试用户',
            ],
        ], $query->toArray());
    }

    public function testWithSpacesInValue(): void
    {
        $query = TermQuery::create('title', 'Hello World');

        self::assertEquals([
            'term' => [
                'title' => 'Hello World',
            ],
        ], $query->toArray());
    }

    public function testWithLargeInteger(): void
    {
        $query = TermQuery::create('timestamp', 1643723400);

        self::assertEquals([
            'term' => [
                'timestamp' => 1643723400,
            ],
        ], $query->toArray());
    }

    public function testMultipleInstancesAreIndependent(): void
    {
        $query1 = TermQuery::create('field1', 'value1');
        $query2 = TermQuery::create('field2', 'value2');

        self::assertNotSame($query1, $query2);
        self::assertNotEquals($query1->toArray(), $query2->toArray());
    }

    public function testConsistentOutput(): void
    {
        $query = TermQuery::create('status', 'active');
        $expected = [
            'term' => [
                'status' => 'active',
            ],
        ];

        // Test multiple calls to ensure consistency
        self::assertEquals($expected, $query->toArray());
        self::assertEquals($expected, $query->toArray());
        self::assertEquals($expected, $query->toArray());
    }

    public function testWithDifferentFieldNames(): void
    {
        $testCases = [
            ['status', 'published'],
            ['category_id', 123],
            ['is_featured', true],
            ['user.role', 'admin'],
            ['tags.keyword', 'important'],
        ];

        foreach ($testCases as [$field, $value]) {
            $query = TermQuery::create($field, $value);

            self::assertEquals([
                'term' => [
                    $field => $value,
                ],
            ], $query->toArray());
        }
    }
}
