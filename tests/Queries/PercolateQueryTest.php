<?php

declare(strict_types=1);

namespace Spatie\ElasticsearchQueryBuilder\Tests\Queries;

use Exception;
use PHPUnit\Framework\TestCase;
use Spatie\ElasticsearchQueryBuilder\Queries\PercolateQuery;

final class PercolateQueryTest extends TestCase
{
    public function testCreateReturnsNewInstance(): void
    {
        $query = PercolateQuery::create('test_field');

        self::assertInstanceOf(PercolateQuery::class, $query);
    }

    public function testInlineDocumentToArrayBuildsCorrectQuery(): void
    {
        $query = (new PercolateQuery('test_field'))
            ->setInlineDocument(['foo' => 'bar', 'baz' => 'qux']);

        self::assertEquals([
            'percolate' => [
                'field' => 'test_field',
                'document' => ['foo' => 'bar', 'baz' => 'qux'],
            ],
        ], $query->toArray());
    }

    public function testDocumentToArrayBuildsCorrectQuery(): void
    {
        $query = (new PercolateQuery('test_field'))
            ->setDocument('test_index', 123);

        self::assertEquals([
            'percolate' => [
                'field' => 'test_field',
                'index' => 'test_index',
                'id' => '123',
            ],
        ], $query->toArray());
    }

    public function testToArrayThrowsExceptionWhenBothInlineDocumentAndDocumentAreSet(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('You can only set an inline document or a document, not both.');

        (new PercolateQuery('test_field'))
            ->setInlineDocument(['foo' => 'bar', 'baz' => 'qux'])
            ->setDocument('test_index', 123)
            ->toArray();
    }

    public function testToArrayThrowsExceptionWhenNeitherInlineDocumentNorDocumentAreSet(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('You must set an inline document or a document.');

        (new PercolateQuery('test_field'))->toArray();
    }
}
