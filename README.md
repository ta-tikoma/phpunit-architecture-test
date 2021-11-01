# PHPUnit Application Architecture Test

**Idea**: write architecture tests as well as feature and unit tests

## Installation

#### Install via composer

```bash
composer require --dev ta-tikoma/phpunit-architecture-test
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

## Test files structure

- tests
    - Architecture
        - SomeTest.php
    - Feature
    - Unit

## How to build Layer

- `$this->layerFromNamespace($namespaceStart)` All object which namespace start from `$namespaceStart` fall in layer.
- `$this->layerFromDirectory($directoryStart)` All object which path start from `$directoryStart` fall in layer.
- `(new \PHPUnit\Architecture\Builders\LayerBuilder)-> ... ->build()` Custom layer. You can use:
    - `includeDirectory`
    - `includeNamespace`
    - `includeObject`
    - `excludeDirectory`
    - `excludeNamespace` 
- `$this->layersFromNamespaceRegex($regex)` Builders multiple layers; regex must return group with name 'layer', it is layer identifier for checked object.

## Asserts

### Dependencies

**Example:** Controllers don't use Repositories only via Services

- `assertDependOn($A, $B)` Layer A must contains dependencies by layer B.
- `assertDoesNotDependOn($A, $B)` Layer A (or layers in array A) must not contains dependencies by layer B (or layers in array B).

### Methods arguments type

- `assertIncomingsFrom($A, $B)` Layer A must contains arguments with types from Layer B
- `assertIncomingsNotFrom($A, $B)` Layer A must not contains arguments with types from Layer B
- `assertOutgoingFrom($A, $B)` Layer A must contains methods return types from Layer B
- `assertOutgoingNotFrom($A, $B)` Layer A must not contains methods return types from Layer B

## Alternatives
- [Deptrac](https://github.com/qossmic/deptrac)
- [PHP Architecture Tester](https://github.com/carlosas/phpat)

#### Advantages
- Dynamic creation of layers by regular expression
- Run along with the rest of tests from [phpunit](https://github.com/sebastianbergmann/phpunit)
- Asserts to method arguments (for check dependent injection)
