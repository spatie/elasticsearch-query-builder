**PACKAGE IN DEVELOPMENT, DO NOT USE YET**

# Build and execute ElasticSearch queries using a fluent PHP API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/elasticsearch-query-builder.svg?style=flat-square)](https://packagist.org/packages/spatie/elasticsearch-query-builder)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/elasticsearch-query-builder/run-tests?label=tests)](https://github.com/spatie/elasticsearch-query-builder/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/elasticsearch-query-builder/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/elasticsearch-query-builder/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/elasticsearch-query-builder.svg?style=flat-square)](https://packagist.org/packages/spatie/elasticsearch-query-builder)

---

This package is a _lightweight_ query builder for ElasticSearch. It was specifically built for our [elasticsearch-search-string-parser](https://github.com/spatie/elasticsearch-search-string-parser) so it covers most use-cases but might lack certain features. We're always open for PRs if you need anything specific!

```php
use Spatie\ElasticsearchQueryBuilder\Aggregations\MaxAggregation;
use Spatie\ElasticsearchQueryBuilder\Builder;
use Spatie\ElasticsearchQueryBuilder\Queries\MatchQuery;

$client = Elasticsearch\ClientBuilder::create()->build();

$companies = (new Builder($client))
    ->index('companies')
    ->addQuery(MatchQuery::create('name', 'spatie', fuzziness: 3))
    ->addAggregation(MaxAggregation::create('score'))
    ->search();
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/elasticsearch-query-builder.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/elasticsearch-query-builder)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/elasticsearch-query-builder
```

## Basic usage

The only class you really need to interact with is the `Spatie\ElasticsearchQueryBuilder\Builder` class. It requires an `\Elasticsearch\Client` passed in the constructor. Take a look at the [ElasticSearch SDK docs](https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/installation.html) to learn more about connecting to your ElasticSearch cluster. 

The `Builder` class contains some methods to add queries, aggregations, sorts, fields and some extras for pagination. You can read more about these methods below. Once you've fully built-up the query you can use `$builder->search()` to execute the query or `$builder->getPayload()` to get the raw payload for ElasticSearch.

```php
use Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery;
use Spatie\ElasticsearchQueryBuilder\Builder;

$client = Elasticsearch\ClientBuilder::create()->build();

$builder = new Builder($client);

$builder->addQuery(RangeQuery::create('age')->gte(18));

$results = $builder->search(); // raw response from ElasticSearch
```

## Adding queries

The `$builder->addQuery()` method can be used to add any of the available `Query` types to the builder. The available query types can be found below or in the `src/Queries` directory of this repo. Every `Query` has a static `create()` method to pass its most important parameters.

The following query types are available:

### `\Spatie\ElasticsearchQueryBuilder\Queries\ExistsQuery`

```php
\Spatie\ElasticsearchQueryBuilder\Queries\ExistsQuery::create('terms_and_conditions');
```

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html)

### `\Spatie\ElasticsearchQueryBuilder\Queries\MatchQuery`

```php
\Spatie\ElasticsearchQueryBuilder\Queries\MatchQuery::create('name', 'john doe', fuzziness: 2);
```

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html)

### `\Spatie\ElasticsearchQueryBuilder\Queries\MultiMatchQuery`

```php
\Spatie\ElasticsearchQueryBuilder\Queries\MultiMatchQuery::create('john', ['email', 'email'], fuzziness: 'auto');
```

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html)

### `\Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery`

```php
\Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery::create(
    'user', 
    new \Spatie\ElasticsearchQueryBuilder\Queries\MatchQuery('name', 'john')
);
```

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-nested-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-nested-query.html)

### `\Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery`

```php
\Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery::create('age')
    ->gte(18)
    ->lte(1337);
```

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html)

### `\Spatie\ElasticsearchQueryBuilder\Queries\TermQuery`

```php
\Spatie\ElasticsearchQueryBuilder\Queries\TermQuery::create('user.id', 'flx');
```

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html)

### `\Spatie\ElasticsearchQueryBuilder\Queries\WildcardQuery`

```php
\Spatie\ElasticsearchQueryBuilder\Queries\WildcardQuery::create('user.id', '*doe');
```

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html)

### `\Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery`

```php
\Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery::create()
    ->add($matchQuery, 'must_not')
    ->add($existsQuery, 'must_not');
```

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html)

### Chaining multiple queries

Multiple `addQuery()` calls can be chained on one `Builder`. Under the hood they'll be added to a `BoolQuery` with occurrence type `must`. By passing a second argument to the `addQuery()` method you can select a different occurrence type:

```php
$builder->addQuery(
    MatchQuery::create('name', 'billie'), 
    'must_not' // available types: must, must_not, should, filter
);
```

More information on the boolean query and its occurrence types can be found [in the ElasticSearch docs](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html).

## Adding aggregations



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alex Vanderbist](https://github.com/alexvanderbist)
- [Ruben Van Assche](https://github.com/rubenvanassche)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
