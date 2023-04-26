<?php

namespace Spatie\QueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\MultiMatchQuery;

class MultiMatchQueryTest extends TestCase
{
    /** @test */
    public function test_operator_present()
    {
        $query = MultiMatchQuery::create('name', ['foo', 'bar'], 2, operator: 'and');
        $this->assertArrayHasKey('operator', $query->toArray()['multi_match']);
        $this->assertEquals('and', $query->toArray()['multi_match']['operator']);
    }

    /** @test */
    public function test_operator_not_present()
    {
        $query = MultiMatchQuery::create('name', ['foo', 'bar'], 2);
        $this->assertArrayNotHasKey('operator', $query->toArray()['multi_match']);
    }

}
