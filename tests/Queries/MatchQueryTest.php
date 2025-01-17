<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Exceptions\InvalidOperatorValue;
use Spatie\ElasticsearchQueryBuilder\Queries\MatchQuery;

class MatchQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = MatchQuery::create('test_field', 'test_value');

        self::assertInstanceOf(MatchQuery::class, $query);
    }

    public function testDefaultSetup(): void
    {
        $query = (new MatchQuery('test_field', 'test_value'));

        self::assertEquals([
            'match' => [
                'test_field' => [
                    'query' => 'test_value',
                    'operator' => 'or',
                ],
            ],
        ], $query->toArray());
    }

    public function testFullSetup(): void
    {
        $query = (new MatchQuery(
            field: 'test_field',
            query: 'test_value',
            fuzziness: 'auto',
            boost: 1,
            operator: 'and'
        ));

        self::assertEquals([
            'match' => [
                'test_field' => [
                    'query' => 'test_value',
                    'fuzziness' => 'auto',
                    'boost' => 1,
                    'operator' => 'and',
                ],
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = MatchQuery::create(
            field: 'test_field',
            query: 'test_value',
            fuzziness: 'auto',
            boost: 1,
            operator: 'and'
        );

        self::assertEquals([
            'match' => [
                'test_field' => [
                    'query' => 'test_value',
                    'fuzziness' => 'auto',
                    'boost' => 1,
                    'operator' => 'and',
                ],
            ],
        ], $query->toArray());
    }

    public function testToThrowsExceptionWhenProvideWrongTypeOfOperator(): void
    {
        $this->expectException(InvalidOperatorValue::class);
        $this->expectExceptionMessage('The operator must be either "or" or "and".');

        MatchQuery::create(
            field: 'test_field',
            query: 'test_value',
            operator: 1
        );
    }
}
