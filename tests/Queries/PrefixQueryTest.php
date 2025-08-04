<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\PrefixQuery;

class PrefixQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = PrefixQuery::create('user', 'john');

        self::assertInstanceOf(PrefixQuery::class, $query);
    }

    public function testDefaultSetup(): void
    {
        $query = new PrefixQuery('user', 'john');

        self::assertEquals([
            'prefix' => [
                'user' => [
                    'value' => 'john',
                ],
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = PrefixQuery::create('title', 'elasticsearch');

        self::assertEquals([
            'prefix' => [
                'title' => [
                    'value' => 'elasticsearch',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithEmptyPrefix(): void
    {
        $query = PrefixQuery::create('name', '');

        self::assertEquals([
            'prefix' => [
                'name' => [
                    'value' => '',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithNumericValue(): void
    {
        $query = PrefixQuery::create('id', '123');

        self::assertEquals([
            'prefix' => [
                'id' => [
                    'value' => '123',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithNestedField(): void
    {
        $query = PrefixQuery::create('user.name', 'admin');

        self::assertEquals([
            'prefix' => [
                'user.name' => [
                    'value' => 'admin',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithSpecialCharacters(): void
    {
        $query = PrefixQuery::create('email', 'test@');

        self::assertEquals([
            'prefix' => [
                'email' => [
                    'value' => 'test@',
                ],
            ],
        ], $query->toArray());
    }
}