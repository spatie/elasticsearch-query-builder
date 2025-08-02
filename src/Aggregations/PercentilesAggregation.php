<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithMissing;

class PercentilesAggregation extends Aggregation
{
    use WithMissing;

    protected string $field;

    protected array $percents;

    protected ?int $compression = null;

    protected ?string $method = null;

    public static function create(string $name, string $field, array $percents): self
    {
        return new self($name, $field, $percents);
    }

    public function __construct(string $name, string $field, array $percents)
    {
        $this->name = $name;
        $this->field = $field;
        $this->percents = $percents;
    }

    public function compression(int $compression): self
    {
        $this->compression = $compression;

        return $this;
    }

    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function payload(): array
    {
        $parameters = [
            'field' => $this->field,
            'percents' => $this->percents,
        ];

        if ($this->compression) {
            $parameters['tdigest'] = [
                'compression' => $this->compression,
            ];
        }

        if ($this->method) {
            $parameters['method'] = $this->method;
        }

        if ($this->missing !== null) {
            $parameters['missing'] = $this->missing;
        }

        return [
            'percentiles' => $parameters,
        ];
    }
}
