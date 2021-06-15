<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts;

class Sort
{
    public const ASC = 'asc';
    public const DESC = 'desc';

    protected string $field;

    protected string $order;

    protected ?string $missing;

    protected ?string $unmappedType;

    public static function create(string $field, string $order): static
    {
        return new self($field, $order);
    }

    public function __construct(string $field, string $order)
    {
        $this->field = $field;
        $this->order = $order;
    }

    public function missing(string $missing): static
    {
        $this->missing = $missing;

        return $this;
    }

    public function unmappedType(string $unmappedType): static
    {
        $this->unmappedType = $unmappedType;

        return $this;
    }

    public function toArray(): array
    {
        $payload = [
            'order' => $this->order,
        ];

        if ($this->missing) {
            $payload['missing'] = $this->missing;
        }

        if ($this->unmappedType) {
            $payload['unmapped_type'] = $this->unmappedType;
        }

        return [
            $this->field => $payload,
        ];
    }
}
