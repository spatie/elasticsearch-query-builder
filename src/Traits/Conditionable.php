<?php

namespace Spatie\ElasticsearchQueryBuilder\Traits;

trait Conditionable
{
    /**
     * Apply the callback if the given "value" is truthy.
     */
    public function when(mixed $value, ?callable $callback = null, ?callable $default = null): static
    {
        if ($value) {
            return $callback($this, $value) ?: $this;
        }

        if ($default) {
            return $default($this, $value) ?: $this;
        }

        return $this;
    }

    /**
     * Apply the callback if the given "value" is falsy.
     */
    public function unless(mixed $value, ?callable $callback = null, ?callable $default = null): static
    {
        if (! $value) {
            return $callback($this, $value) ?: $this;
        }

        if ($default) {
            return $default($this, $value) ?: $this;
        }

        return $this;
    }
}
