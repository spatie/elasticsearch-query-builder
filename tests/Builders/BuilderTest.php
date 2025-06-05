<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Builders;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Spatie\ElasticsearchQueryBuilder\Builder;
use Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery\InnerHits;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class BuilderTest extends TestCase
{
    public function testGeneratesCollapseWithPlainArrayData(): void
    {
        $innerHits = [
            'name' => 'first_group',
            'size' => 1,
            'sort' => [
                [ 'name.keyword' => [ 'order' => 'asc' ] ],
            ],
        ];

        $builder = (new Builder())
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

        $builder = (new Builder())
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
        $payload = (new Builder())
            ->minScore(0.1)
            ->getPayload();

        $this->assertArrayHasKey('min_score', $payload);
        $this->assertEquals(0.1, $payload['min_score']);
    }
}
