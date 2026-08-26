# Ok Bloomer
An autoscaling [Bloom filter](https://en.wikipedia.org/wiki/Bloom_filter) with ultra-low memory footprint for PHP. Ok Bloomer employs a novel layered filtering strategy that allows it to expand while maintaining an upper bound on the false positive rate. Each layer is comprised of a bitmap that remembers the hash signatures of the items inserted so far. If an item gets caught in the filter, then it has probably been seen before. However, if an item passes through the filter, then it definitely has never been seen before.

- **Ultra-low** memory footprint
- **Autoscaling** works on streaming data
- **Bounded** maximum false positive rate
- **Open-source** and free to use commercially

## Installation
Install into your project using [Composer](https://getcomposer.org/):

```sh
$ composer require andrewdalpino/okbloomer
```

### Requirements
- [PHP](https://php.net/manual/en/install.php) 7.4 or above

## Bloom Filter
A probabilistic data structure that estimates the prior occurrence of a given item with a maximum false positive rate.

### Parameters
| # | Name | Default | Type | Description |
|---|---|---|---|---|
| 1 | maxFalsePositiveRate | 0.01 | float | The false positive rate to remain below. |
| 2 | numHashes | 4 | int, null | The number of hash functions used, i.e. the number of slices per layer. Set to `null` for auto. |
| 3 | layerSize | 32000000 | int | The size of each layer of the filter in bits. |

### Example

```php
use OkBloomer\BloomFilter;

$filter = new BloomFilter(0.01, 4, 32000000);

$filter->insert('foo');

echo $filter->exists('foo');

echo $filter->existsOrInsert('bar');

echo $filter->exists('bar');
```

```
true 

false

true
```

## Testing
To run the unit tests:

```sh
$ composer test
```
## Static Analysis
To run static code analysis:

```sh
$ composer analyze
```

## Benchmarks
To run the benchmarks:

```sh
$ composer benchmark
```

## References
- [1] P. S. Almeida et al. (2007). Scalable Bloom Filters.
