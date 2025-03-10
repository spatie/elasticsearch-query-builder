# Changelog

All notable changes to `elasticsearch-query-builder` will be documented in this file.

## 3.6.0 - 2025-03-10

### What's Changed

* Add the min_score to the builder by @wgriffioen in https://github.com/spatie/elasticsearch-query-builder/pull/68

### New Contributors

* @wgriffioen made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/68

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/3.5.1...3.6.0

## 3.5.1 - 2025-02-19

### What's Changed

* Collapse: Make use of nested inner hits query object by @sventendo in https://github.com/spatie/elasticsearch-query-builder/pull/67

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/3.5.0...3.5.1

## 3.5.0 - 2025-02-11

### What's Changed

* Add elasticsearch `collapse` on query builder by @chirag-techrayslabs in https://github.com/spatie/elasticsearch-query-builder/pull/65

### New Contributors

* @chirag-techrayslabs made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/65

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/3.4.0...3.5.0

## 3.4.0 - 2025-02-10

### What's Changed

* Add MultiBuilder to support multi-search API  by @chrispappas in https://github.com/spatie/elasticsearch-query-builder/pull/64

### New Contributors

* @chrispappas made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/64

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/3.3.0...3.4.0

## 3.3.0 - 2025-01-20

### What's Changed

* Add boost parameter to Terms query by @floristenhove in https://github.com/spatie/elasticsearch-query-builder/pull/54
* Nested query improvements by @dam-bal in https://github.com/spatie/elasticsearch-query-builder/pull/53

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/3.2.2...3.3.0

## 3.2.2 - 2025-01-17

### What's Changed

* Fix missing args for create func by @l3aro in https://github.com/spatie/elasticsearch-query-builder/pull/60

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/3.2.1...3.2.2

## 3.2.1 - 2025-01-17

### What's Changed

* Support operator in MatchQuery by @l3aro in https://github.com/spatie/elasticsearch-query-builder/pull/59

### New Contributors

* @l3aro made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/59

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/3.2.0...3.2.1

## 3.2.0 - 2024-11-21

### What's Changed

* Add percolate query by @floristenhove in https://github.com/spatie/elasticsearch-query-builder/pull/57
* Allow setting values to null for RangeQuery by @bram-pkg in https://github.com/spatie/elasticsearch-query-builder/pull/56
* Add geoshape query by @floristenhove in https://github.com/spatie/elasticsearch-query-builder/pull/58

### New Contributors

* @floristenhove made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/57
* @bram-pkg made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/56

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/3.1.0...3.2.0

## 3.1.0 - 2024-10-03

### What's Changed

* feat: add MatchPhraseQuery by @summerKK in https://github.com/spatie/elasticsearch-query-builder/pull/50

### New Contributors

* @summerKK made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/50

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/3.0.0...3.1.0

## 3.0.0 - 2024-05-06

See [upgrading.md](./UPGRADING.md) for possible breaking changes.

### What's Changed

* Added Nested Sort by @dam-bal in https://github.com/spatie/elasticsearch-query-builder/pull/46

### New Contributors

* @dam-bal made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/46

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.7.0...3.0.0

## 2.7.0 - 2024-05-06

### What's Changed

* Allow `bool` and `int` as types for term query by @sventendo in https://github.com/spatie/elasticsearch-query-builder/pull/34
* Fix: Include `size` and `from` in getPayload by @harlequin410 in https://github.com/spatie/elasticsearch-query-builder/pull/35
* Allow filter aggregation without using nested aggregations by @sventendo in https://github.com/spatie/elasticsearch-query-builder/pull/37

### New Contributors

* @sventendo made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/34
* @harlequin410 made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/35

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.6.0...2.7.0

## 2.6.0 - 2024-04-25

### What's Changed

* Add minimum_should_match support for BoolQuery by @srowan in https://github.com/spatie/elasticsearch-query-builder/pull/38

### New Contributors

* @srowan made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/38

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.5.0...2.6.0

## 2.5.0 - 2024-04-25

### What's Changed

* Add boost parameter to MatchQuery by @MilanLamote in https://github.com/spatie/elasticsearch-query-builder/pull/42

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.4.0...2.5.0

## 2.4.0 - 2024-04-19

### What's Changed

* Add post filter logic and enhance readme by @MilanLamote in https://github.com/spatie/elasticsearch-query-builder/pull/41

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.3.0...2.4.0

## 2.3.0 - 2024-04-17

### What's Changed

* Add highlighting by @MilanLamote in https://github.com/spatie/elasticsearch-query-builder/pull/39

### New Contributors

* @MilanLamote made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/39

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.2.0...2.3.0

## 2.2.0 - 2024-03-26

### What's Changed

* Added able track total hits by @nick-rashkevich in https://github.com/spatie/elasticsearch-query-builder/pull/36

### New Contributors

* @nick-rashkevich made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/36

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.1.0...2.2.0

## 2.1.0 - 2023-02-17

### What's Changed

- IB-1280 added sum aggregation by @webbaard in https://github.com/spatie/elasticsearch-query-builder/pull/26

### New Contributors

- @webbaard made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/26

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.0.1...2.1.0

## 2.0.1 - 2022-10-10

### What's Changed

- Fix `Builder::search()` return data type by @imdhemy in https://github.com/spatie/elasticsearch-query-builder/pull/24

### New Contributors

- @imdhemy made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/24

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.0.0...2.0.1

## 2.0.0 - 2022-07-22

### What's Changed

- Elasticseach ^8.0 support by @h-rafiee in https://github.com/spatie/elasticsearch-query-builder/pull/19

### New Contributors

- @h-rafiee made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/19

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/1.4.0...2.0.0

## 1.4.0 - 2022-07-20

- add `TermsQuery`

## 1.3.0 - 2021-08-06

- add `PrefixQuery`

## 1.2.2 - 2021-07-29

- remove debug statements (again :facepalm:)

## 1.2.1 - 2021-07-29

- remove debug statements

## 1.2.0 - 2021-07-28

- add `type` to `MultiMatchQuery`

## 1.1.2 - 2021-07-22

- fix `search_after` parameter in request body

## 1.1.1 - 2021-07-22

- add `search_after` to request body

## 1.1.0 - 2021-07-22

- provide default sort order
- add `searchAfter` method

## 1.0.0 - 2021-07-07

- initial release
