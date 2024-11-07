<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

use Exception;

class PercolateQuery implements Query
{
    protected string $field;
    protected array $document;
    protected string $index;
    protected string $id;

    public static function create(string $field): static
    {
        return new self($field);
    }

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function setInlineDocument(array $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function setDocument(string $index, string|int $id): static
    {
        $this->index = $index;
        $this->id = (string) $id;

        return $this;
    }

    public function toArray(): array
    {
        if (isset($this->document) && isset($this->index)) {
            throw new Exception('You can only set an inline document or a document, not both.');
        }

        if (! isset($this->document) && ! isset($this->index)) {
            throw new Exception('You must set an inline document or a document.');
        }

        $query = [
            'percolate' => [
                'field' => $this->field,
            ],
        ];

        if (isset($this->document)) {
            $query['percolate']['document'] = $this->document;

            return $query;
        }

        $query['percolate']['index'] = $this->index;
        $query['percolate']['id'] = $this->id;

        return $query;
    }
}
