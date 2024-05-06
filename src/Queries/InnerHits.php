<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class InnerHits
{
    public static function create(): self
    {
        return new InnerHits();
    }

    public function __construct(
        protected ?int $from = null,
        protected ?int $size = null,
        protected ?string $name = null
    ) {
    }

    public function from(int $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPayload(): array
    {
        return array_filter(
            [
                'from' => $this->from,
                'size' => $this->size,
                'name' => $this->name,
            ]
        );
    }
}
