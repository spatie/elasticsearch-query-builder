<?php

namespace Spatie\QueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\MatchQuery;

class MatchQueryTest extends TestCase
{
    /** @test */
    public function test_operator_present()
    {
        $query = MatchQuery::create('name', 'value', 2, operator: 'and');
        $this->assertArrayHasKey('operator', $query->toArray()['match']['name']);
        $this->assertEquals('and', $query->toArray()['match']['name']['operator']);
    }

    /** @test */
    public function test_operator_not_present()
    {
        $query = MatchQuery::create('name', 'value', 2);
        $this->assertArrayNotHasKey('operator', $query->toArray()['match']['name']);
    }

}
