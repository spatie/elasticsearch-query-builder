<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns;

trait WithMissing
{
    protected string|int|float|bool|null $missing = null;

    public function missing(string|int|float|bool $missingValue): self
    {
        $this->missing = $missingValue;

        return $this;
    }
}
