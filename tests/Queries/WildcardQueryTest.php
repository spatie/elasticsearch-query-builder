<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\WildcardQuery;

class WildcardQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = WildcardQuery::create('name', 'john*');

        self::assertInstanceOf(WildcardQuery::class, $query);
    }

    public function testDefaultSetup(): void
    {
        $query = new WildcardQuery('name', 'john*');

        self::assertEquals([
            'wildcard' => [
                'name' => [
                    'value' => 'john*',
                ],
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = WildcardQuery::create('email', '*@gmail.com');

        self::assertEquals([
            'wildcard' => [
                'email' => [
                    'value' => '*@gmail.com',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithAsteriskWildcard(): void
    {
        $query = WildcardQuery::create('title', 'elasticsearch*');

        self::assertEquals([
            'wildcard' => [
                'title' => [
                    'value' => 'elasticsearch*',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithQuestionMarkWildcard(): void
    {
        $query = WildcardQuery::create('code', 'test?ng');

        self::assertEquals([
            'wildcard' => [
                'code' => [
                    'value' => 'test?ng',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithMultipleWildcards(): void
    {
        $query = WildcardQuery::create('filename', '*.txt');

        self::assertEquals([
            'wildcard' => [
                'filename' => [
                    'value' => '*.txt',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithComplexPattern(): void
    {
        $query = WildcardQuery::create('path', '/usr/*/bin/?sh');

        self::assertEquals([
            'wildcard' => [
                'path' => [
                    'value' => '/usr/*/bin/?sh',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithNoWildcards(): void
    {
        $query = WildcardQuery::create('status', 'active');

        self::assertEquals([
            'wildcard' => [
                'status' => [
                    'value' => 'active',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithEmptyString(): void
    {
        $query = WildcardQuery::create('field', '');

        self::assertEquals([
            'wildcard' => [
                'field' => [
                    'value' => '',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithOnlyWildcards(): void
    {
        $query = WildcardQuery::create('field', '*');

        self::assertEquals([
            'wildcard' => [
                'field' => [
                    'value' => '*',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithQuestionMarkOnly(): void
    {
        $query = WildcardQuery::create('field', '?');

        self::assertEquals([
            'wildcard' => [
                'field' => [
                    'value' => '?',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithNestedField(): void
    {
        $query = WildcardQuery::create('user.email', '*@company.com');

        self::assertEquals([
            'wildcard' => [
                'user.email' => [
                    'value' => '*@company.com',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithSpecialCharacters(): void
    {
        $query = WildcardQuery::create('url', 'https://*.example.com/*');

        self::assertEquals([
            'wildcard' => [
                'url' => [
                    'value' => 'https://*.example.com/*',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithNumericPattern(): void
    {
        $query = WildcardQuery::create('id', '2023*');

        self::assertEquals([
            'wildcard' => [
                'id' => [
                    'value' => '2023*',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithMixedWildcards(): void
    {
        $query = WildcardQuery::create('pattern', 'a*b?c*');

        self::assertEquals([
            'wildcard' => [
                'pattern' => [
                    'value' => 'a*b?c*',
                ],
            ],
        ], $query->toArray());
    }

    public function testConsistentOutput(): void
    {
        $query = WildcardQuery::create('name', 'test*');
        $expected = [
            'wildcard' => [
                'name' => [
                    'value' => 'test*',
                ],
            ],
        ];

        // Test multiple calls to ensure consistency
        self::assertEquals($expected, $query->toArray());
        self::assertEquals($expected, $query->toArray());
        self::assertEquals($expected, $query->toArray());
    }

    public function testMultipleInstancesAreIndependent(): void
    {
        $query1 = WildcardQuery::create('field1', 'value1*');
        $query2 = WildcardQuery::create('field2', '*value2');

        self::assertNotSame($query1, $query2);
        self::assertNotEquals($query1->toArray(), $query2->toArray());
    }

    public function testWithVariousPatterns(): void
    {
        $patterns = [
            'prefix*',
            '*suffix',
            '*middle*',
            'pre*suf',
            'te?t',
            '??pattern',
            'pattern??',
            '?*?',
            '*?*',
        ];

        foreach ($patterns as $pattern) {
            $query = WildcardQuery::create('field', $pattern);

            self::assertEquals([
                'wildcard' => [
                    'field' => [
                        'value' => $pattern,
                    ],
                ],
            ], $query->toArray());
        }
    }

    public function testWithUnicodeCharacters(): void
    {
        $query = WildcardQuery::create('name', '测试*');

        self::assertEquals([
            'wildcard' => [
                'name' => [
                    'value' => '测试*',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithSpacesInPattern(): void
    {
        $query = WildcardQuery::create('title', 'Hello * World');

        self::assertEquals([
            'wildcard' => [
                'title' => [
                    'value' => 'Hello * World',
                ],
            ],
        ], $query->toArray());
    }
}
