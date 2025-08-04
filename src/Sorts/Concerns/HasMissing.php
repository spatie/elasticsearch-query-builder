<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts\Concerns;

trait HasMissing
{
    protected string|int|float|bool|null $missing = null;

    public function missing(string|int|float|bool $missing): static
    {
        $this->missing = $missing;

        return $this;
    }
}
