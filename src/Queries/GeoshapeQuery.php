<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

use Spatie\ElasticsearchQueryBuilder\Enums\GeoshapeType;
use Spatie\ElasticsearchQueryBuilder\Enums\SpatialRelation;

class GeoshapeQuery implements Query
{
    protected string $field;
    protected GeoshapeType $type;
    protected array $coordinates;
    protected ?SpatialRelation $relation;

    public static function create(string $field, GeoshapeType $type, array $coordinates = null, ?SpatialRelation $relation = null): static
    {
        return new self($field, $type, $coordinates, $relation);
    }

    public function __construct(string $field, GeoshapeType $type, ?array $coordinates = null, ?SpatialRelation $relation = null)
    {
        $this->field = $field;
        $this->type = $type;
        $this->coordinates = $coordinates;
        $this->relation = $relation;
    }

    public function toArray(): array
    {
        return [
            'geo_shape' => [
                $this->field => [
                    'shape' => [
                        'type' => $this->type->value,
                        'coordinates' => $this->coordinates,
                    ],
                    'relation' => $this->relation ? $this->relation->value : SpatialRelation::INTERSECTS->value,
                ]
            ],
        ];
    }
}
