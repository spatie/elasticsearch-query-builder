<?php

namespace Spatie\ElasticsearchQueryBuilder\Exceptions;

use InvalidArgumentException;

class InvalidOperatorValue extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('The operator must be either "or" or "and".');
    }
}
