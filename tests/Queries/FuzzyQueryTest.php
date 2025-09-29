<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\FuzzyQuery;

class FuzzyQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = FuzzyQuery::create('name', 'john');

        self::assertInstanceOf(FuzzyQuery::class, $query);
    }

    public function testDefaultSetup(): void
    {
        $query = new FuzzyQuery('name', 'john');

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithFuzziness(): void
    {
        $query = new FuzzyQuery('name', 'john', 'auto');

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                    'fuzziness' => 'auto',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithNumericFuzziness(): void
    {
        $query = new FuzzyQuery('name', 'john', 2);

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                    'fuzziness' => 2,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithMaxExpansions(): void
    {
        $query = new FuzzyQuery('name', 'john', null, 50);

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                    'max_expansions' => 50,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithPrefixLength(): void
    {
        $query = new FuzzyQuery('name', 'john', null, null, 2);

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                    'prefix_length' => 2,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithTranspositions(): void
    {
        $query = new FuzzyQuery('name', 'john', null, null, null, false);

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                    'transpositions' => false,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithBoost(): void
    {
        $query = new FuzzyQuery('name', 'john', null, null, null, null, 2.5);

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                    'boost' => 2.5,
                ],
            ],
        ], $query->toArray());
    }

    public function testFullSetup(): void
    {
        $query = new FuzzyQuery(
            field: 'name',
            value: 'john',
            fuzziness: 'auto',
            maxExpansions: 50,
            prefixLength: 1,
            transpositions: true,
            boost: 1.5
        );

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                    'fuzziness' => 'auto',
                    'max_expansions' => 50,
                    'prefix_length' => 1,
                    'transpositions' => true,
                    'boost' => 1.5,
                ],
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = FuzzyQuery::create(
            field: 'title',
            value: 'elasticsearch',
            fuzziness: 2,
            maxExpansions: 100,
            prefixLength: 3,
            transpositions: false,
            boost: 2.0
        );

        self::assertEquals([
            'fuzzy' => [
                'title' => [
                    'value' => 'elasticsearch',
                    'fuzziness' => 2,
                    'max_expansions' => 100,
                    'prefix_length' => 3,
                    'transpositions' => false,
                    'boost' => 2.0,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithRewrite(): void
    {
        $query = new FuzzyQuery('name', 'john', null, null, null, null, null, 'constant_score_blended');

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                    'rewrite' => 'constant_score_blended',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithAllParameters(): void
    {
        $query = new FuzzyQuery('name', 'john', 'AUTO', 50, 2, true, 1.5, 'constant_score');

        self::assertEquals([
            'fuzzy' => [
                'name' => [
                    'value' => 'john',
                    'fuzziness' => 'AUTO',
                    'max_expansions' => 50,
                    'prefix_length' => 2,
                    'transpositions' => true,
                    'boost' => 1.5,
                    'rewrite' => 'constant_score',
                ],
            ],
        ], $query->toArray());
    }
}
