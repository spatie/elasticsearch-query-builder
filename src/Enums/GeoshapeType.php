<?php

namespace Spatie\ElasticsearchQueryBuilder\Enums;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/geo-shape.html#input-structure
 */
enum GeoshapeType: string
{
    case POINT = 'point';
    case LINE_STRING = 'linestring';
    case POLYGON = 'polygon';
    case MULTI_POINT = 'multipoint';
    case MULTI_LINE_STRING = 'multilinestring';
    case MULTI_POLYGON = 'multipolygon';
    case GEOMETRY_COLLECTION = 'geometrycollection';
    case ENVELOPE = 'envelope';
}
