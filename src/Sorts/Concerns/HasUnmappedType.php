<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts\Concerns;

trait HasUnmappedType
{
    private ?string $unmappedType = null;

    public function unmappedType(string $unmappedType): static
    {
        $this->unmappedType = $unmappedType;

        return $this;
    }
}
