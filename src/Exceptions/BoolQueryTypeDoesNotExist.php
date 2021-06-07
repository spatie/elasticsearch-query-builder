<?php

namespace Spatie\ElasticsearchQueryBuilder\Exceptions;

use InvalidArgumentException;

class BoolQueryTypeDoesNotExist extends InvalidArgumentException
{
    public function __construct(string $type)
    {
        parent::__construct("Type `$type` for bool query does not exist. Please use one of the following: must, must_not, filter, should");
    }
}
