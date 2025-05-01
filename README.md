# Build and execute ElasticSearch queries using a fluent PHP API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/elasticsearch-query-builder.svg?style=flat-square)](https://packagist.org/packages/spatie/elasticsearch-query-builder)
[![Tests](https://github.com/spatie/elasticsearch-query-builder/actions/workflows/run-tests.yml/badge.svg)](https://github.com/spatie/elasticsearch-query-builder/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/elasticsearch-query-builder.svg?style=flat-square)](https://packagist.org/packages/spatie/elasticsearch-query-builder)

---

This package is a _lightweight_ query builder for ElasticSearch. It was specifically built for our [elasticsearch-search-string-parser](https://github.com/spatie/elasticsearch-search-string-parser) so it covers most use-cases but might lack certain features. We're always open for PRs if you need anything specific!

```php
use Spatie\ElasticsearchQueryBuilder\Aggregations\MaxAggregation;
use Spatie\ElasticsearchQueryBuilder\Builder;
use Spatie\ElasticsearchQueryBuilder\Queries\MatchQuery;

$client = Elastic\Elasticsearch\ClientBuilder::create()->build();

$query = (new Builder())
    ->index('companies')
    ->addQuery(MatchQuery::create('name', 'spatie', fuzziness: 3))
    ->addAggregation(MaxAggregation::create('score'))
    ->params();
$companies = $client->search($query);
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

> **Note**
> If you're using `elasticsearch/elasticsearch` v7 you need to use [v1](https://github.com/spatie/elasticsearch-query-builder/tree/v1) of this package.

## Basic usage

The only class you really need to interact with is the `Spatie\ElasticsearchQueryBuilder\Builder` class.

The `Builder` class contains some methods to [add queries](#adding-queries), [aggregations](#adding-aggregations), [sorts](#adding-sorts), [fields](#retrieve-specific-fields) and some extras for [pagination](#pagination). You can read more about these methods below. Once you've fully built-up the query you can use `$builder->params()` to get the full payload for ElasticSearch.

```php
use Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery;
use Spatie\ElasticsearchQueryBuilder\Builder;

$client = Elastic\Elasticsearch\ClientBuilder::create()->build();

$builder = new Builder();

$builder->addQuery(RangeQuery::create('age')->gte(18));

$params = $builder->params(); 
$results = $client->search($params); // raw response from ElasticSearch
```

#### Multi-Search Queries

Multi-Search queries are also available using the [`MultiBuilder` class](#multi-search-query-builder).

## Adding queries

The `$builder->addQuery()` method can be used to add any of the available `Query` types to the builder. The available query types can be found below or in the `src/Queries` directory of this repo. Every `Query` has a static `create()` method to pass its most important parameters.

The following query types are available:

#### `ExistsQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\ExistsQuery::create('terms_and_conditions');
```

#### `GeoshapeQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-shape-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-shape-query.html);

```php
\Spatie\ElasticsearchQueryBuilder\Queries\GeoshapeQuery::create(
  'location',
  \Spatie\ElasticsearchQueryBuilder\Queries\GeoshapeQuery::TYPE_POLYGON,
  [[1.0, 2.0]],
  \Spatie\ElasticsearchQueryBuilder\Queries\GeoShapeQuery::RELATION_INTERSECTS,
);
```

#### `MatchQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\MatchQuery::create('name', 'john doe', fuzziness: 2, boost: 5.0);
```

#### `MatchPhraseQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query-phrase.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query-phrase.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\MatchPhraseQuery::create('name', 'john doe', slop: 2,zeroTermsQuery: "none",analyzer: "my_analyzer");
```

#### `MultiMatchQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\MultiMatchQuery::create('john', ['email', 'email'], fuzziness: 'auto');
```

#### `NestedQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-nested-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-nested-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery::create(
    'user',
    new \Spatie\ElasticsearchQueryBuilder\Queries\MatchQuery('name', 'john')
);
```

##### `NestedQuery` `InnerHits`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/inner-hits.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/inner-hits.html)

```php
$nestedQuery = \Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery::create(
    'comments',
    \Spatie\ElasticsearchQueryBuilder\Queries\TermsQuery::create('comments.published', true)
);

$nestedQuery->innerHits(
    \Spatie\ElasticsearchQueryBuilder\Queries\NestedQuery\InnerHits::create('top_three_liked_comments')
        ->size(3)
        ->addSort(
            \Spatie\ElasticsearchQueryBuilder\Sorts\Sort::create(
                'comments.likes',
                \Spatie\ElasticsearchQueryBuilder\Sorts\Sort::DESC
            )
        )
        ->fields(['comments.content', 'comments.author', 'comments.likes'])
);
```

#### `RangeQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery::create('age')
    ->gte(18)
    ->lte(1337);
```

#### `TermQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\TermQuery::create('user.id', 'flx');
```

#### `TermsQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\TermsQuery::create('user.id', ['flx', 'fly'], boost: 5.0);
```

#### `WildcardQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\WildcardQuery::create('user.id', '*doe');
```

#### `PercolateQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-percolate-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-percolate-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\PercolateQuery::create('query', ['title' => 'foo', 'body' => 'bar']);
```

#### `BoolQuery`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html)

```php
\Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery::create()
    ->add($matchQuery, 'must_not')
    ->add($existsQuery, 'must_not');
```

#### `collapse`

The `collapse` feature allows grouping search results by a specific field while retrieving top documents from each group using `inner_hits`. This is useful for avoiding duplicate entities in search results while still accessing grouped data.

[https://www.elastic.co/guide/en/elasticsearch/reference/current/collapse-search-results.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/collapse-search-results.html)

```php
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;
use Spatie\ElasticsearchQueryBuilder\Builder;

// Initialize ExtendedBuilder with an Elasticsearch client
$builder = new Builder();

// Apply collapse to group by 'user_id'
$builder->collapse(
    'user_id', // Field to collapse on
    [
        'name' => 'top_three_liked_posts',
        'size' => 3, // Retrieve top 3 posts per user
        'sort' => [
            Sort::create('post.likes', Sort::DESC), // Sort posts by likes (descending)
        ],
        'fields' => ['post.title', 'post.content', 'post.likes'], // Select specific fields
    ],
    10, // Max concurrent group searches
);

// Execute the search
$params = $builder->params();
$response = $client->search($params);
```

### Chaining multiple queries

Multiple `addQuery()` calls can be chained on one `Builder`. Under the hood they'll be added to a `BoolQuery` with occurrence type `must`. By passing a second argument to the `addQuery()` method you can select a different occurrence type:

```php
$builder
    ->addQuery(
        MatchQuery::create('name', 'billie'),
        'must_not' // available types: must, must_not, should, filter
    )
    ->addQuery(
        MatchQuery::create('team', 'eillish')
    );
```

More information on the boolean query and its occurrence types can be found [in the ElasticSearch docs](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html).

## Adding aggregations

The `$builder->addAggregation()` method can be used to add any of the available `Aggregation`s to the builder. The available aggregation types can be found below or in the `src/Aggregations` directory of this repo. Every `Aggregation` has a static `create()` method to pass its most important parameters and sometimes some extra methods.

```php
use Spatie\ElasticsearchQueryBuilder\Aggregations\TermsAggregation;
use Spatie\ElasticsearchQueryBuilder\Builder;

$client = Elastic\Elasticsearch\ClientBuilder::create()->build();
$params = (new Builder())
    ->addAggregation(TermsAggregation::create('genres', 'genre'))
    ->params();
$results = $client->search($params);

$genres = $results['aggregations']['genres']['buckets'];
```

The following query types are available:

#### `CardinalityAggregation`

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\CardinalityAggregation::create('team_agg', 'team_name');
```

#### `FilterAggregation`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-filter-aggregation.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-filter-aggregation.html)

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\FilterAggregation::create(
    'tshirts',
    \Spatie\ElasticsearchQueryBuilder\Queries\TermQuery::create('type', 'tshirt'),
    \Spatie\ElasticsearchQueryBuilder\Aggregations\MaxAggregation::create('max_price', 'price')
);
```

#### `MaxAggregation`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-max-aggregation.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-max-aggregation.html)

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\MaxAggregation::create('max_price', 'price');
```

#### `MinAggregation`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-min-aggregation.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-min-aggregation.html)

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\MinAggregation::create('min_price', 'price');
```

#### `SumAggregation`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-sum-aggregation.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-sum-aggregation.html)

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\SumAggregation::create('sum_price', 'price');
```

#### `NestedAggregation`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-nested-aggregation.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-nested-aggregation.html)

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\NestedAggregation::create(
    'resellers',
    'resellers',
    \Spatie\ElasticsearchQueryBuilder\Aggregations\MinAggregation::create('min_price', 'resellers.price'),
    \Spatie\ElasticsearchQueryBuilder\Aggregations\MaxAggregation::create('max_price', 'resellers.price'),
);
```

#### `ReverseNestedAggregation`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-reverse-nested-aggregation.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-reverse-nested-aggregation.html)

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\ReverseNestedAggregation::create(
    'name',
    ...$aggregations
);
```

#### `TermsAggregation`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html)

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\TermsAggregation::create(
    'genres',
    'genre'
)
    ->size(10)
    ->order(['_count' => 'asc'])
    ->missing('N/A')
    ->aggregation(/* $subAggregation */);
```

#### `TopHitsAggregation`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-top-hits-aggregation.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-top-hits-aggregation.html)

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\TopHitsAggregation::create(
    'top_sales_hits',
    size: 10,
);
```

#### `DateHistogramAggregation`

[https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-datehistogram-aggregation.html](hhttps://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-datehistogram-aggregation.html)

```php
\Spatie\ElasticsearchQueryBuilder\Aggregations\DateHistogramAggregation::create(
    'name',
    '@timestamp'
    '1h',
)
    ->aggregation(/* $subAggregation */);
```

## Adding sorts

The `Builder` (and some aggregations) has a `addSort()` method that takes a `Sort` instance to sort the results. You can read more about how sorting works in [the ElasticSearch docs](https://www.elastic.co/guide/en/elasticsearch/reference/current/sort-search-results.html).

```php
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

$builder
    ->addSort(Sort::create('age', Sort::DESC))
    ->addSort(
        Sort::create('score', Sort::ASC)
            ->unmappedType('long')
            ->missing(0)
    );
```

### Nested sort

[https://www.elastic.co/guide/en/elasticsearch/reference/current/sort-search-results.html#\_nested_sorting_examples](https://www.elastic.co/guide/en/elasticsearch/reference/current/sort-search-results.html#_nested_sorting_examples)

```php
use Spatie\ElasticsearchQueryBuilder\Sorts\NestedSort;

$builder
    ->addSort(
        NestedSort::create('books', 'books.rating', NestedSort::ASC)
    );
```

#### Nested sort with filter

```php
use Spatie\ElasticsearchQueryBuilder\Sorts\NestedSort;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermQuery;

$builder
    ->addSort(
        NestedSort::create(
            'books',
            'books.rating',
            NestedSort::ASC
        )->filter(BoolQuery::create()->add(TermQuery::create('books.category', 'comedy'))
    );
```

## Retrieve specific fields

The `fields()` method can be used to request specific fields from the resulting documents without returning the entire `_source` entry. You can read more about the specifics of the fields parameter in [the ElasticSearch docs](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-fields.html).

```php
$builder->fields('user.id', 'http.*.status');
```

## Highlighting

The `highlight()` method can be used to add a highlight section to your query along the rules in [the ElasticSearch docs](https://www.elastic.co/guide/en/elasticsearch/reference/current/highlighting.html).

```php
$highlightSettings = [
    'pre_tags' => ['<em>'],
    'post_tags' => ['</em>'],
    'fields' => [
        '*' => (object) []
    ]
];

$builder->highlight($highlightSettings);
```

## Post filter

The `addPostFilterQuery()` method can be used to add a post_filter BoolQuery to your query along the rules in [the ElasticSearch docs](https://www.elastic.co/guide/en/elasticsearch/reference/current/filter-search-results.html#post-filter).

```php
use Spatie\ElasticsearchQueryBuilder\Queries\TermsQuery;

$builder->addPostFilterQuery(TermsQuery::create('user.id', ['flx', 'fly']));
```

## Pagination

Finally the `Builder` also features a `size()` and `from()` method for the corresponding ElasticSearch search parameters. These can be used to build a paginated search. Take a look the following example to get a rough idea:

```php
use Spatie\ElasticsearchQueryBuilder\Builder;

$pageSize = 100;
$pageNumber = $_GET['page'] ?? 1;
$client = Elastic\Elasticsearch\ClientBuilder::create();
$pageResults = (new Builder())
    ->size($pageSize)
    ->from(($pageNumber - 1) * $pageSize)
    ->params();
$pageResults = $client->search($params);
```

## Multi-Search Query Builder

Elasticsearch provides a ["multi-search" API](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-multi-search.html) that allows for multiple query bodies to be included in a single request.

Use the `MultiBuilder` class and [add builders](#add-builders) to add builders to your query request. The response will include a `responses` array of the query results, in the same order the requests are added. Use the `$multiBuilder->search()` to execute the queries, or `$multiBuilder->getPayload()` for the raw request payload.

```php
use Spatie\ElasticsearchQueryBuilder\MultiBuilder;
use Spatie\ElasticsearchQueryBuilder\Builder;

$client = Elastic\Elasticsearch\ClientBuilder::create();
$multiBuilder = (new MultiBuilder());

$multiBuilder->addBuilder(
    (new Builder())->index('custom_index')->size(10)
);
// you can pass the index name to the addBuilder method second param
$multiBuilder->addBuilder(
    (new Builder())->size(10)
    'different_index'
);

$params = $multiBuilder->search();
$multiResults = $client->msearch($params);
```

Returns the following response JSON shape:

```
{
    "took": 2,
    "responses": [
        {... first query result ...},
        {... second query result ...},
    ]
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Alex Vanderbist](https://github.com/alexvanderbist)
-   [Ruben Van Assche](https://github.com/rubenvanassche)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
