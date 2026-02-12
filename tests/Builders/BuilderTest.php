<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Builders;

use Elastic\Elasticsearch\Client;
use Elastic\Transport\TransportBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Spatie\ElasticsearchQueryBuilder\Builder;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery\InnerHits;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class BuilderTest extends TestCase
{
    protected Client $client;

    public function setUp(): void
    {
        $transport = TransportBuilder::create()
            ->setClient(new \Http\Mock\Client())
            ->build();

        $logger = $this->createStub(LoggerInterface::class);

        $this->client = new Client($transport, $logger);
    }

    public function testGeneratesCollapseWithPlainArrayData(): void
    {
        $innerHits = [
            'name' => 'first_group',
            'size' => 1,
            'sort' => [
                [ 'name.keyword' => [ 'order' => 'asc' ] ],
            ],
        ];

        $builder = (new Builder($this->client))
            ->collapse('group_id', $innerHits);

        self::assertEquals(
            [ 'collapse' => [ 'field' => 'group_id', 'inner_hits' => $innerHits ] ],
            $builder->getPayload()
        );
    }

    public function testGeneratesCollapseWithInnerHitsObject(): void
    {
        $innerHits = InnerHits::create('first_group')
            ->size(1)
            ->addSort(new Sort('name.keyword', 'asc'));

        $builder = (new Builder($this->client))
            ->collapse('group_id', $innerHits);

        self::assertEquals(
            [
                'collapse' => [
                    'field' => 'group_id', 'inner_hits' => [
                        'name' => 'first_group',
                        'size' => 1,
                        'sort' => [
                            [
                                'name.keyword' => [
                                    'order' => 'asc',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $builder->getPayload()
        );
    }

    public function testMinScoreIsAppliedToThePayload(): void
    {
        $payload = (new Builder($this->client))
            ->minScore(0.1)
            ->getPayload();

        $this->assertArrayHasKey('min_score', $payload);
        $this->assertEquals(0.1, $payload['min_score']);
    }

    public function testWhereAddsTermQueryToPayload(): void
    {
        $payload = (new Builder($this->client))
            ->where('name', 'zs')
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'term' => [
                                'name' => 'zs',
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereSupportsThreeArgumentsWithGreaterThanOrEqualOperator(): void
    {
        $payload = (new Builder($this->client))
            ->where('age', '>=', 1)
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'range' => [
                                'age' => [
                                    'gte' => 1,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereSupportsThreeArgumentsWithNotEqualOperator(): void
    {
        $payload = (new Builder($this->client))
            ->where('status', '!=', 'archived')
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must_not' => [
                        [
                            'term' => [
                                'status' => 'archived',
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testOrWhereWithNotEqualOperatorKeepsShouldOccurrence(): void
    {
        $payload = (new Builder($this->client))
            ->orWhere('status', '!=', 'archived')
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'bool' => [
                                'must_not' => [
                                    [
                                        'term' => [
                                            'status' => 'archived',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testOrWhereWithNullEqualOperatorKeepsShouldOccurrence(): void
    {
        $payload = (new Builder($this->client))
            ->orWhere('status', '=', null)
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'bool' => [
                                'must_not' => [
                                    [
                                        'exists' => [
                                            'field' => 'status',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testOrWhereWithNotInOperatorKeepsShouldOccurrence(): void
    {
        $payload = (new Builder($this->client))
            ->orWhere('user.id', 'not in', ['flx', 'fly'])
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'bool' => [
                                'must_not' => [
                                    [
                                        'terms' => [
                                            'user.id' => ['flx', 'fly'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testOrWhereWithNotBetweenOperatorKeepsShouldOccurrence(): void
    {
        $payload = (new Builder($this->client))
            ->orWhere('age', 'not between', [18, 65])
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'bool' => [
                                'must_not' => [
                                    [
                                        'range' => [
                                            'age' => [
                                                'lte' => 65,
                                                'gte' => 18,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereSupportsInOperator(): void
    {
        $payload = (new Builder($this->client))
            ->where('user.id', 'in', ['flx', 'fly'])
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'terms' => [
                                'user.id' => ['flx', 'fly'],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereSupportsBetweenOperator(): void
    {
        $payload = (new Builder($this->client))
            ->where('age', 'between', [18, 65])
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'range' => [
                                'age' => [
                                    'lte' => 65,
                                    'gte' => 18,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testOrWhereAddsTermQueryToShouldOccurrence(): void
    {
        $payload = (new Builder($this->client))
            ->orWhere('team', 'A')
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'term' => [
                                'team' => 'A',
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereNotAddsTermQueryToMustNotOccurrence(): void
    {
        $payload = (new Builder($this->client))
            ->whereNot('status', 'archived')
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must_not' => [
                        [
                            'term' => [
                                'status' => 'archived',
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereInAddsTermsQueryToPayload(): void
    {
        $payload = (new Builder($this->client))
            ->whereIn('user.id', ['flx', 'fly'])
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'terms' => [
                                'user.id' => ['flx', 'fly'],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereNotInAddsTermsQueryToMustNotOccurrence(): void
    {
        $payload = (new Builder($this->client))
            ->whereNotIn('user.id', ['flx', 'fly'], 5.0)
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must_not' => [
                        [
                            'terms' => [
                                'user.id' => ['flx', 'fly'],
                                'boost' => 5.0,
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereBetweenAddsRangeQueryToPayload(): void
    {
        $payload = (new Builder($this->client))
            ->whereBetween('age', [18, 65])
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'range' => [
                                'age' => [
                                    'lte' => 65,
                                    'gte' => 18,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereNotBetweenAddsRangeQueryToMustNotOccurrence(): void
    {
        $payload = (new Builder($this->client))
            ->whereNotBetween('age', [18, 65])
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must_not' => [
                        [
                            'range' => [
                                'age' => [
                                    'lte' => 65,
                                    'gte' => 18,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereNullAddsMustNotExistsQuery(): void
    {
        $payload = (new Builder($this->client))
            ->whereNull('deleted_at')
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must_not' => [
                        [
                            'exists' => [
                                'field' => 'deleted_at',
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereNotNullAddsExistsQuery(): void
    {
        $payload = (new Builder($this->client))
            ->whereNotNull('deleted_at')
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'exists' => [
                                'field' => 'deleted_at',
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWherePrefixAddsPrefixQuery(): void
    {
        $payload = (new Builder($this->client))
            ->wherePrefix('user.id', 'fl')
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'prefix' => [
                                'user.id' => [
                                    'value' => 'fl',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereRegexpAddsRegexpQuery(): void
    {
        $payload = (new Builder($this->client))
            ->whereRegexp('name', 'jo.*', flags: 'ALL', maxDeterminizedStates: 10000, boost: 2.5, rewrite: 'constant_score')
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'regexp' => [
                                'name' => [
                                    'value' => 'jo.*',
                                    'flags' => 'ALL',
                                    'max_determinized_states' => 10000,
                                    'boost' => 2.5,
                                    'rewrite' => 'constant_score',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereClosureReceivesBuilder(): void
    {
        $payload = (new Builder($this->client))
            ->where(function (Builder $query): void {
                $query->where('first_name', 'john')
                    ->orWhere('last_name', 'doe');
            })
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'bool' => [
                                'must' => [
                                    [
                                        'term' => [
                                            'first_name' => 'john',
                                        ],
                                    ],
                                ],
                                'should' => [
                                    [
                                        'term' => [
                                            'last_name' => 'doe',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereClosureReceivesBoolQuery(): void
    {
        $payload = (new Builder($this->client))
            ->where(function (BoolQuery $bool): void {
                $bool->add(\Spatie\ElasticsearchQueryBuilder\Queries\TermQuery::create('is_active', true))
                    ->add(\Spatie\ElasticsearchQueryBuilder\Queries\TermQuery::create('is_deleted', true), 'must_not');
            })
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'bool' => [
                                'must' => [
                                    [
                                        'term' => [
                                            'is_active' => true,
                                        ],
                                    ],
                                ],
                                'must_not' => [
                                    [
                                        'term' => [
                                            'is_deleted' => true,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }

    public function testWhereClosureWithNoConditionsDoesNotChangePayload(): void
    {
        $payload = (new Builder($this->client))
            ->where(function (Builder $query): void {
            })
            ->getPayload();

        self::assertEquals([], $payload);
    }

    public function testNestedWhereClosureCanBuildComplexQueries(): void
    {
        $payload = (new Builder($this->client))
            ->where('tenant_id', 'acme')
            ->where(function (Builder $query): void {
                $query->where('category', 'book')
                    ->orWhere(function (Builder $nestedQuery): void {
                        $nestedQuery->whereBetween('price', [10, 100])
                            ->whereNotNull('published_at');
                    });
            })
            ->getPayload();

        self::assertEquals([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'term' => [
                                'tenant_id' => 'acme',
                            ],
                        ],
                        [
                            'bool' => [
                                'must' => [
                                    [
                                        'term' => [
                                            'category' => 'book',
                                        ],
                                    ],
                                ],
                                'should' => [
                                    [
                                        'bool' => [
                                            'must' => [
                                                [
                                                    'range' => [
                                                        'price' => [
                                                            'lte' => 100,
                                                            'gte' => 10,
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'exists' => [
                                                        'field' => 'published_at',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payload);
    }
}
