<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

use InvalidArgumentException;

class GeoshapeQuery implements Query
{
    public const TYPE_POINT = 'point';
    public const TYPE_LINESTRING = 'linestring';
    public const TYPE_POLYGON = 'polygon';
    public const TYPE_MULTIPOINT = 'multipoint';
    public const TYPE_MULTILINESTRING = 'multilinestring';
    public const TYPE_MULTIPOLYGON = 'multipolygon';
    public const TYPE_GEOMETRYCOLLECTION = 'geometrycollection';
    public const TYPE_ENVELOPE = 'envelope';
    public const RELATION_INTERSECTS = 'intersects';
    public const RELATION_DISJOINT = 'disjoint';
    public const RELATION_CONTAINS = 'contains';
    public const RELATION_WITHIN = 'within';

    protected string $field;
    protected string $type;
    protected array $coordinates;
    protected ?string $relation;

    public function __construct(
        string $field,
        string $type,
        array $coordinates,
        ?string $relation = self::RELATION_INTERSECTS,
    ) {
        $this->field = $field;
        $this->coordinates = $coordinates;

        if (! in_array($type, [
            self::TYPE_POINT,
            self::TYPE_LINESTRING,
            self::TYPE_POLYGON,
            self::TYPE_MULTIPOINT,
            self::TYPE_MULTILINESTRING,
            self::TYPE_MULTIPOLYGON,
            self::TYPE_GEOMETRYCOLLECTION,
            self::TYPE_ENVELOPE,
        ], true)) {
            throw new InvalidArgumentException('Invalid type provided');
        }

        $this->type = $type;

        if (! in_array($relation, [
            self::RELATION_INTERSECTS,
            self::RELATION_DISJOINT,
            self::RELATION_CONTAINS,
            self::RELATION_WITHIN,
        ], true)) {
            throw new InvalidArgumentException('Invalid relation provided');
        }

        $this->relation = $relation;
    }

    public static function create(
        string $field,
        string $type,
        array $coordinates,
        ?string $relation = self::RELATION_INTERSECTS,
    ): static {
        return new self($field, $type, $coordinates, $relation);
    }

    public function toArray(): array
    {
        return [
            'geo_shape' => [
                $this->field => [
                    'shape' => [
                        'type' => $this->type,
                        'coordinates' => $this->coordinates,
                    ],
                    'relation' => $this->relation ? $this->relation : self::RELATION_INTERSECTS,
                ],
            ],
        ];
    }
}
