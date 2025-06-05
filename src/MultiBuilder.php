<?php

namespace Spatie\ElasticsearchQueryBuilder;

class MultiBuilder
{
    protected ?array $builders = [];

    public function addBuilder(Builder $builder, ?string $indexName = null): static
    {
        $this->builders[] = [
            'index' => $indexName ?? $builder->getIndex(),
            'builder' => $builder,
        ];

        return $this;
    }

    public function getPayload(): array
    {
        $payload = [];

        foreach ($this->builders as $builderInstance) {
            ['index' => $index, 'builder' => $builder] = $builderInstance;
            $payload[] = $index ? ['index' => $index] : [];
            $payload[] = $builder->getPayload();
        }

        return $payload;
    }

    public function params(): array
    {
        $payload = $this->getPayload();

        $params = [
            'body' => $payload,
        ];

        return $params;
    }
}
