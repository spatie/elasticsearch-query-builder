<?php

namespace Spatie\ElasticsearchQueryBuilder\Enums;

enum SpatialRelation: string
{
    case INTERSECTS = 'intersects';
    case DISJOINT = 'disjoint';
    case WITHIN = 'within';
    case CONTAINS = 'contains';
}
