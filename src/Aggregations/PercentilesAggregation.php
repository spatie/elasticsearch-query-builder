<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithMissing;

class PercentilesAggregation extends Aggregation
{
    use WithMissing;

    protected string $field;

    protected array $percents;

    protected ?bool $keyed = null;

    protected ?int $compression = null;

    protected ?string $executionHint = null;

    protected ?int $numberOfSignificantValueDigits = null;

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

    public function keyed(bool $keyed = true): self
    {
        $this->keyed = $keyed;

        return $this;
    }

    public function compression(int $compression): self
    {
        $this->compression = $compression;

        return $this;
    }

    public function tdigest(int $compression, ?string $executionHint = null): self
    {
        $this->compression = $compression;
        $this->executionHint = $executionHint;

        return $this;
    }

    public function hdr(int $numberOfSignificantValueDigits): self
    {
        $this->numberOfSignificantValueDigits = $numberOfSignificantValueDigits;

        return $this;
    }

    public function payload(): array
    {
        $parameters = [
            'field' => $this->field,
            'percents' => $this->percents,
        ];

        // Add keyed parameter
        if ($this->keyed !== null) {
            $parameters['keyed'] = $this->keyed;
        }

        // Add TDigest configuration
        if ($this->compression !== null || $this->executionHint !== null) {
            $tdigest = [];
            if ($this->compression !== null) {
                $tdigest['compression'] = $this->compression;
            }
            if ($this->executionHint !== null) {
                $tdigest['execution_hint'] = $this->executionHint;
            }
            $parameters['tdigest'] = $tdigest;
        }

        // Add HDR configuration
        if ($this->numberOfSignificantValueDigits !== null) {
            $parameters['hdr'] = [
                'number_of_significant_value_digits' => $this->numberOfSignificantValueDigits,
            ];
        }

        // Add missing value handling
        if ($this->missing !== null) {
            $parameters['missing'] = $this->missing;
        }

        return [
            'percentiles' => $parameters,
        ];
    }
}
