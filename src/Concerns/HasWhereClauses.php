<?php

namespace Spatie\ElasticsearchQueryBuilder\Concerns;

use Closure;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;
use Spatie\ElasticsearchQueryBuilder\Builder;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\ExistsQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\PrefixQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\RegexpQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermsQuery;

trait HasWhereClauses
{
    public function where(string|Closure $field, mixed $operatorOrValue = null, mixed $value = null, string $boolType = 'must'): static
    {
        if ($field instanceof Closure) {
            $resolvedBoolType = is_string($operatorOrValue) ? $operatorOrValue : $boolType;

            return $this->addWhereGroup($field, $resolvedBoolType);
        }

        if (! $this->isWhereOperator($operatorOrValue)) {
            if ($operatorOrValue === null) {
                return $this->whereNull($field);
            }

            return $this->addQuery(TermQuery::create($field, $operatorOrValue), $boolType);
        }

        return $this->applyWhereOperator($field, (string) $operatorOrValue, $value, $boolType);
    }

    public function orWhere(string|Closure $field, mixed $operatorOrValue = null, mixed $value = null): static
    {
        return $this->where($field, $operatorOrValue, $value, 'should');
    }

    public function whereNot(string|Closure $field, mixed $operatorOrValue = null, mixed $value = null): static
    {
        return $this->where($field, $operatorOrValue, $value, 'must_not');
    }

    public function whereIn(string $field, array $values, string $boolType = 'must', null|float $boost = null): static
    {
        return $this->addQuery(TermsQuery::create($field, $values, $boost), $boolType);
    }

    public function whereNotIn(string $field, array $values, null|float $boost = null): static
    {
        return $this->whereIn($field, $values, 'must_not', $boost);
    }

    public function whereBetween(string $field, array $range, string $boolType = 'must'): static {
        return $this->addQuery(
            RangeQuery::create($field)->gte($range[0])->lte($range[1]),
            $boolType
        );
    }

    public function whereNotBetween(string $field, array $range): static {
        return $this->whereBetween($field, $range, 'must_not');
    }

    public function whereNull(string $field): static
    {
        return $this->whereExists($field, 'must_not');
    }

    public function whereNotNull(string $field): static
    {
        return $this->whereExists($field);
    }

    public function whereExists(string $field, string $boolType = 'must'): static
    {
        return $this->addQuery(ExistsQuery::create($field), $boolType);
    }

    public function wherePrefix(string $field, string|int $query, string $boolType = 'must'): static
    {
        return $this->addQuery(PrefixQuery::create($field, $query), $boolType);
    }

    public function whereRegexp(
        string $field,
        string $value,
        string $boolType = 'must',
        ?string $flags = null,
        ?int $maxDeterminizedStates = null,
        ?float $boost = null,
        ?string $rewrite = null
    ): static {
        return $this->addQuery(
            RegexpQuery::create($field, $value, $flags, $maxDeterminizedStates, $boost, $rewrite),
            $boolType
        );
    }

    private function addWhereGroup(Closure $callback, string $boolType): static
    {
        $nestedBuilder = new self($this->client);
        $nestedBoolQuery = BoolQuery::create();

        $argument = $this->resolveWhereCallbackArgument($callback, $nestedBuilder, $nestedBoolQuery);

        if ($argument === null) {
            $callback();
        } else {
            $callback($argument);
        }

        if ($nestedBuilder->query && ! $nestedBuilder->query->isEmpty()) {
            if ($nestedBoolQuery->isEmpty()) {
                return $this->addQuery($nestedBuilder->query, $boolType);
            }

            $nestedBoolQuery->add($nestedBuilder->query);
        }

        if ($nestedBoolQuery->isEmpty()) {
            return $this;
        }

        return $this->addQuery($nestedBoolQuery, $boolType);
    }

    private function resolveWhereCallbackArgument(Closure $callback, Builder $nestedBuilder, BoolQuery $nestedBoolQuery): Builder|BoolQuery|null {
        $reflection = new ReflectionFunction($callback);
        $firstParameter = $reflection->getParameters()[0] ?? null;

        if (! $firstParameter) {
            return null;
        }

        $parameterType = $firstParameter->getType();

        if ($this->parameterAcceptsClass($parameterType, Builder::class)) {
            return $nestedBuilder;
        }

        if ($this->parameterAcceptsClass($parameterType, BoolQuery::class)) {
            return $nestedBoolQuery;
        }

        return $nestedBuilder;
    }

    private function parameterAcceptsClass(?ReflectionType $type, string $className): bool
    {
        if (! $type) {
            return false;
        }

        if ($type instanceof ReflectionNamedType) {
            return ! $type->isBuiltin() && $type->getName() === $className;
        }

        if ($type instanceof ReflectionUnionType) {
            foreach ($type->getTypes() as $unionedType) {
                if ($unionedType instanceof ReflectionNamedType
                    && ! $unionedType->isBuiltin()
                    && $unionedType->getName() === $className) {
                    return true;
                }
            }
        }

        return false;
    }

    private function applyWhereOperator(string $field, string $operator, mixed $value, string $boolType): static
    {
        $normalizedOperator = strtolower(trim($operator));

        return match ($normalizedOperator) {
            '=' => $value === null ? $this->whereNull($field) : $this->addQuery(TermQuery::create($field, $value), $boolType),
            '!=', '<>' => $value === null ? $this->whereNotNull($field) : $this->addQuery(TermQuery::create($field, $value), 'must_not'),
            '>' => $this->addQuery(RangeQuery::create($field)->gt($value), $boolType),
            '>=' => $this->addQuery(RangeQuery::create($field)->gte($value), $boolType),
            '<' => $this->addQuery(RangeQuery::create($field)->lt($value), $boolType),
            '<=' => $this->addQuery(RangeQuery::create($field)->lte($value), $boolType),
            'in' => $this->whereIn($field, is_array($value) ? $value : [$value], $boolType),
            'not in' => $this->whereNotIn($field, is_array($value) ? $value : [$value]),
            'between' => $this->whereBetween($field, $value[0] ?? null, $value[1] ?? null, $boolType),
            'not between' => $this->whereNotBetween($field, $value[0] ?? null, $value[1] ?? null),
            default => $this->addQuery(TermQuery::create($field, $value), $boolType),
        };
    }

    private function isWhereOperator(mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        return in_array(strtolower(trim($value)), [
            '=',
            '!=',
            '<>',
            '>',
            '>=',
            '<',
            '<=',
            'in',
            'not in',
            'between',
            'not between',
        ], true);
    }
}
