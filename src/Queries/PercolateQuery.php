<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class PercolateQuery implements Query
{
    protected string $field;

    protected array $document;

    public static function create(string $field, array $document): static
    {
        return new self($field, $document);
    }

    public function __construct(string $field, array $document)
    {
        $this->field = $field;
        $this->document = $document;
    }

    public function toArray(): array
    {
        return [
            'percolate' => [
                'field' => $this->field,
                'document' => $this->document,
            ],
        ];
    }
}
