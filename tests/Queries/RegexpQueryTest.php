<?php

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\RegexpQuery;

class RegexpQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = RegexpQuery::create('user', '.*john.*');

        self::assertInstanceOf(RegexpQuery::class, $query);
    }

    public function testDefaultSetup(): void
    {
        $query = new RegexpQuery('user', '.*john.*');

        self::assertEquals([
            'regexp' => [
                'user' => [
                    'value' => '.*john.*',
                ],
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunction(): void
    {
        $query = RegexpQuery::create('email', '.*@gmail\.com');

        self::assertEquals([
            'regexp' => [
                'email' => [
                    'value' => '.*@gmail\.com',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithFlags(): void
    {
        $query = new RegexpQuery('title', '[a-z]+', 'CASE_INSENSITIVE');

        self::assertEquals([
            'regexp' => [
                'title' => [
                    'value' => '[a-z]+',
                    'flags' => 'CASE_INSENSITIVE',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithMultipleFlags(): void
    {
        $query = new RegexpQuery('content', '.*test.*', 'CASE_INSENSITIVE|DOTALL');

        self::assertEquals([
            'regexp' => [
                'content' => [
                    'value' => '.*test.*',
                    'flags' => 'CASE_INSENSITIVE|DOTALL',
                ],
            ],
        ], $query->toArray());
    }

    public function testWithMaxDeterminizedStates(): void
    {
        $query = new RegexpQuery('name', '.*complex.*pattern.*', null, 50000);

        self::assertEquals([
            'regexp' => [
                'name' => [
                    'value' => '.*complex.*pattern.*',
                    'max_determinized_states' => 50000,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithBoost(): void
    {
        $query = new RegexpQuery('title', 'important.*', null, null, 2.5);

        self::assertEquals([
            'regexp' => [
                'title' => [
                    'value' => 'important.*',
                    'boost' => 2.5,
                ],
            ],
        ], $query->toArray());
    }

    public function testFullSetup(): void
    {
        $query = new RegexpQuery(
            field: 'description',
            value: '.*elasticsearch.*query.*',
            flags: 'CASE_INSENSITIVE',
            maxDeterminizedStates: 20000,
            boost: 1.5
        );

        self::assertEquals([
            'regexp' => [
                'description' => [
                    'value' => '.*elasticsearch.*query.*',
                    'flags' => 'CASE_INSENSITIVE',
                    'max_determinized_states' => 20000,
                    'boost' => 1.5,
                ],
            ],
        ], $query->toArray());
    }

    public function testSetupWithStaticCreateFunctionFullParams(): void
    {
        $query = RegexpQuery::create(
            field: 'url',
            value: 'https?://.*\.com(/.*)?',
            flags: 'DOTALL',
            maxDeterminizedStates: 30000,
            boost: 0.5
        );

        self::assertEquals([
            'regexp' => [
                'url' => [
                    'value' => 'https?://.*\.com(/.*)?',
                    'flags' => 'DOTALL',
                    'max_determinized_states' => 30000,
                    'boost' => 0.5,
                ],
            ],
        ], $query->toArray());
    }

    public function testWithSimplePatterns(): void
    {
        $patterns = [
            'test.*',
            '[0-9]+',
            '(abc|def)',
            '\d{4}-\d{2}-\d{2}',
        ];

        foreach ($patterns as $pattern) {
            $query = RegexpQuery::create('field', $pattern);

            self::assertEquals([
                'regexp' => [
                    'field' => [
                        'value' => $pattern,
                    ],
                ],
            ], $query->toArray());
        }
    }

    public function testWithEscapedCharacters(): void
    {
        $query = RegexpQuery::create('path', '/home/user/.*\.txt');

        self::assertEquals([
            'regexp' => [
                'path' => [
                    'value' => '/home/user/.*\.txt',
                ],
            ],
        ], $query->toArray());
    }
}