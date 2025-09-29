<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\ExistsQuery;

class ExistsQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = ExistsQuery::create('user');

        self::assertInstanceOf(ExistsQuery::class, $query);
    }

    public function testDefaultSetup(): void
    {
        $query = new ExistsQuery('user');

        self::assertEquals([
            'exists' => [
                'field' => 'user',
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = ExistsQuery::create('email');

        self::assertEquals([
            'exists' => [
                'field' => 'email',
            ],
        ], $query->toArray());
    }

    public function testWithNestedField(): void
    {
        $query = ExistsQuery::create('user.email');

        self::assertEquals([
            'exists' => [
                'field' => 'user.email',
            ],
        ], $query->toArray());
    }

    public function testWithDifferentFieldNames(): void
    {
        $fields = ['title', 'description', 'tags', 'created_at'];

        foreach ($fields as $field) {
            $query = ExistsQuery::create($field);

            self::assertEquals([
                'exists' => [
                    'field' => $field,
                ],
            ], $query->toArray());
        }
    }
}
