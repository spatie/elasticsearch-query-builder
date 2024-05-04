<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts\Concerns;

trait HasMissing
{
    protected ?string $missing = null;

    public function missing(string $missing): static
    {
        $this->missing = $missing;

        return $this;
    }
}
