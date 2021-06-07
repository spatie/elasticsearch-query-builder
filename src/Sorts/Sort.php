<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts;

class Sort
{
    public const ASC = 'asc';
    public const DESC = 'desc';

    protected string $field;

    protected string $order;

    public static function create(string $field, string $order): static
    {
        return new self($field, $order);
    }

    public function __construct(string $field, string $order)
    {
        $this->field = $field;
        $this->order = $order;
    }

    public function toArray(): array
    {
        return [
            $this->field => [
                'order' => $this->order,
            ],
        ];
    }
}
