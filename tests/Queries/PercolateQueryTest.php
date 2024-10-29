<?php

declare(strict_types=1);

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\PercolateQuery;

final class PercolateQueryTest extends TestCase
{
    public function testToArrayBuildsCorrectQuery(): void
    {
        $query = new PercolateQuery('test_field', ['foo' => 'bar', 'baz' => 'qux']);

        self::assertEquals([
            'percolate' => [
                'field' => 'test_field',
                'document' => ['foo' => 'bar', 'baz' => 'qux'],
            ],
        ], $query->toArray());
    }
}
