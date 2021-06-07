<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class RangeQuery implements Query
{
    protected null|int|float|string $gte = null;

    protected null|int|float|string $lt = null;

    protected null|int|float|string $lte = null;

    protected null|int|float|string $gt = null;

    public static function create(string $field): self
    {
        return new self($field);
    }

    public function __construct(protected string $field)
    {
    }

    public function lt(int|float|string $value): self
    {
        $this->lt = $value;

        return $this;
    }

    public function lte(int|float|string $value): self
    {
        $this->lte = $value;

        return $this;
    }

    public function gt(int|float|string $value): self
    {
        $this->gt = $value;

        return $this;
    }

    public function gte(int|float|string $value): self
    {
        $this->gte = $value;

        return $this;
    }

    public function toArray(): array
    {
        $parameters = [];

        if ($this->lt !== null) {
            $parameters['lt'] = $this->lt;
        }

        if ($this->lte !== null) {
            $parameters['lte'] = $this->lte;
        }

        if ($this->gt !== null) {
            $parameters['gt'] = $this->gt;
        }

        if ($this->gte !== null) {
            $parameters['gte'] = $this->gte;
        }

        return [
            'range' => [
                $this->field => $parameters,
            ],
        ];
    }
}
