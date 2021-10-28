# PHPUnit Application Architecture Test

*****Idea**: write architecture tests as well as feature and unit tests

## Installation

#### Install via composer

```bash
composer required-dev ta-tikoma/phpunit-architecture-tests
```

#### Add trait to Test class

```php
abstract class TestCase extends BaseTestCase
{
    use ArchitectureAsserts;
}
```

## Use

- Create test
- Make layers of application
- Add asserts

```php
    public function test_make_layer_from_namespace()
    {
        $app = LayerBuilder::fromNamespace('PHPUnit\\Architecture');
        $tests = LayerBuilder::fromNamespace('tests');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

```

#### Run
```bash
./vendor/bin/phpunit
```

## How to build Layer

- `PHPUnit\Architecture\Builders\LayerBuilder::fromNamespace($namespaceStart)` All object which namespace start from `$namespaceStart` fall in layer.
- `PHPUnit\Architecture\Builders\LayerBuilder::fromDirectory($directoryStart)` All object which path start from `$directoryStart` fall in layer.
- `(new \PHPUnit\Architecture\Builders\LayerBuilder)-> ... ->build()` Custom layer; you can use `includeDirectory`, `includeNamespace`, `excludeDirectory`, `excludeNamespace` to build it.
- `PHPUnit\Architecture\Builders\LayersBuilder::fromNamespaceRegex($regex)` Builders multiple layers; regex must return group with name 'layer', it is layer identifier for checked object.

## Asserts

- `assertDependOn($A, $B)` Layer A must contains dependencies by layer B.
- `assertDoesNotDependOn($A, $B)` Layer A (or layers in array A) must not contains dependencies by layer B (or layers in array B).

## Alternatives
- [Deptrac](https://github.com/qossmic/deptrac)
- [PHP Architecture Tester](https://github.com/carlosas/phpat)

#### Advantages
- Dynamic creation of layers by regular expression
- Run along with the rest of tests from [phpunit](https://github.com/sebastianbergmann/phpunit)
