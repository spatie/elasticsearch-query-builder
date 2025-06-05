<?php

declare(strict_types=1);

namespace Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\RawQuery;

final class RawQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = RawQuery::create(['match' => ['field' => 'value']]);

        self::assertInstanceOf(RawQuery::class, $query);
    }

    public function testRawQuery(): void
    {
        $query = [
            'match' => [
                'field' => 'value',
            ],
        ];

        $rawQuery = new RawQuery($query);

        $this->assertSame($query, $rawQuery->toArray());
    }
}
