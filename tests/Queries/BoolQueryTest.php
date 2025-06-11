<?php

namespace Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermQuery;

final class BoolQueryTest extends TestCase
{
    public function testCreate(): void
    {
        $query = BoolQuery::create();

        $this->assertInstanceOf(BoolQuery::class, $query);
    }

    public function testAddMustQuery(): void
    {
        $query = BoolQuery::create()
            ->add(new TermQuery('field', 'value'));

        $this->assertEquals([
            'bool' => [
                'must' => [
                    ['term' => ['field' => 'value']],
                ],
            ],
        ], $query->toArray());
    }

    public function testAddFilterQuery(): void
    {
        $query = BoolQuery::create()
            ->add(new TermQuery('field', 'value'), 'filter');

        $this->assertEquals([
            'bool' => [
                'filter' => [
                    ['term' => ['field' => 'value']],
                ],
            ],
        ], $query->toArray());
    }

    public function testAddShouldQuery(): void
    {
        $query = BoolQuery::create()
            ->add(new TermQuery('field', 'value'), 'should');

        $this->assertEquals([
            'bool' => [
                'should' => [
                    ['term' => ['field' => 'value']],
                ],
            ],
        ], $query->toArray());
    }

    public function testAddMustNotQuery(): void
    {
        $query = BoolQuery::create()
            ->add(new TermQuery('field', 'value'), 'must_not');

        $this->assertEquals([
            'bool' => [
                'must_not' => [
                    ['term' => ['field' => 'value']],
                ],
            ],
        ], $query->toArray());
    }

    public function testAddMultipleQueries(): void
    {
        $query = BoolQuery::create()
            ->add(new TermQuery('field1', 'value1'))
            ->add(new TermQuery('field2', 'value2'), 'filter')
            ->add(new TermQuery('field3', 'value3'), 'should')
            ->add(new TermQuery('field4', 'value4'), 'must_not');

        $this->assertEquals([
            'bool' => [
                'must' => [
                    ['term' => ['field1' => 'value1']],
                ],
                'filter' => [
                    ['term' => ['field2' => 'value2']],
                ],
                'should' => [
                    ['term' => ['field3' => 'value3']],
                ],
                'must_not' => [
                    ['term' => ['field4' => 'value4']],
                ],
            ],
        ], $query->toArray());
    }


    public function testMinimumShouldMatch(): void
    {
        $query = BoolQuery::create()
            ->add(new TermQuery('field1', 'value1'), 'should')
            ->add(new TermQuery('field2', 'value2'), 'should')
            ->minimumShouldMatch('1');

        $this->assertEquals([
            'bool' => [
                'should' => [
                    ['term' => ['field1' => 'value1']],
                    ['term' => ['field2' => 'value2']],
                ],
                'minimum_should_match' => '1',
            ],
        ], $query->toArray());

        $this->assertFalse($query->isEmpty());
    }

    public function testIsEmpty(): void
    {
        $query = BoolQuery::create();

        $this->assertTrue($query->isEmpty());

        $query->add(new TermQuery('field', 'value'));

        $this->assertFalse($query->isEmpty());
    }
}
