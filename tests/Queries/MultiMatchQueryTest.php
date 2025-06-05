<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Exceptions\InvalidOperatorValue;
use Spatie\ElasticsearchQueryBuilder\Queries\MultiMatchQuery;

final class MultiMatchQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = MultiMatchQuery::create('test_value', ['field1', 'field2']);

        self::assertInstanceOf(MultiMatchQuery::class, $query);
    }

    public function testDefaultSetup(): void
    {
        $query = (new MultiMatchQuery('test_value', ['field1', 'field2']));

        self::assertEquals([
            'multi_match' => [
                'query' => 'test_value',
                'fields' => ['field1', 'field2'],
            ],
        ], $query->toArray());
    }

    public function testFullSetup(): void
    {
        $query = (new MultiMatchQuery(
            query: 'test_value',
            fields: ['field1', 'field2'],
            fuzziness: 'auto',
            type: MultiMatchQuery::TYPE_PHRASE,
            operator: 'and',
            boost: 1.5,
            prefixLength: 1,
            maxExpansions: 50,
        ));

        self::assertEquals([
            'multi_match' => [
                'query' => 'test_value',
                'fields' => ['field1', 'field2'],
                'fuzziness' => 'auto',
                'type' => 'phrase',
                'operator' => 'and',
                'boost' => 1.5,
                'prefix_length' => 1,
                'max_expansions' => 50,
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = MultiMatchQuery::create(
            query: 'test_value',
            fields: ['field1', 'field2'],
            fuzziness: 'auto',
            type: MultiMatchQuery::TYPE_PHRASE,
            operator: 'and',
            boost: 1.5,
            prefixLength: 1,
            maxExpansions: 50,
        );

        self::assertEquals([
            'multi_match' => [
                'query' => 'test_value',
                'fields' => ['field1', 'field2'],
                'fuzziness' => 'auto',
                'type' => 'phrase',
                'operator' => 'and',
                'boost' => 1.5,
                'prefix_length' => 1,
                'max_expansions' => 50,
            ],
        ], $query->toArray());
    }

    public function testInvalidOperatorThrowsException(): void
    {
        $this->expectException(InvalidOperatorValue::class);

        new MultiMatchQuery(
            query: 'test_value',
            fields: ['field1', 'field2'],
            operator: 'invalid_operator'
        );
    }
}
