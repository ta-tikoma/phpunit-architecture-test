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
        $app = $this->layerFromNameStart('PHPUnit\\Architecture');
        $tests = $this->layerFromNameStart('tests');

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

- `$this->layerFromNameStart($nameStart)` All object which names start from `$nameStart` fall in layer.
- `$this->layerFromPath($path)` All object which path start from `$path` fall in layer.
- `$this->layer()-> ... ->build()` Custom layer. You can use:
    - `includePath`
    - `includeNameStart`
    - `includeNameRegex`
    - `includeObject`
    - `includeObjectType`
    - `excludePath`
    - `excludeNameStart` 
    - `excludeNameRegex` 
    - `excludeObjectType` 
- `$this->layersFromNameRegex($regex)` Builders multiple layers; regex must return group with name 'layer', it is layer identifier for checked object.
- `$this->layersFromClosure($closure)` Builders multiple layers; Closure take ObjectDescription in param and must to return string (unique module id) or null.

## Asserts

### Dependencies

**Example:** Controllers don't use Repositories only via Services

- `assertDependOn($A, $B)` Layer A must contains dependencies by layer B.
- `assertDoesNotDependOn($A, $B)` Layer A (or layers in array A) must not contains dependencies by layer B (or layers in array B).

### Methods 

- `assertIncomingsFrom($A, $B)` Layer A must contains arguments with types from Layer B
- `assertIncomingsNotFrom($A, $B)` Layer A must not contains arguments with types from Layer B
- `assertOutgoingFrom($A, $B)` Layer A must contains methods return types from Layer B
- `assertOutgoingNotFrom($A, $B)` Layer A must not contains methods return types from Layer B
- `assertMethodSizeLessThan($A, $SIZE)` Layer A must not contains methods with size less than SIZE

### Properties

- `assertHasNotPublicProperties($A)` Objects in Layer A must not contains public properties

## Alternatives
- [Deptrac](https://github.com/qossmic/deptrac)
- [PHP Architecture Tester](https://github.com/carlosas/phpat)

#### Advantages
- Dynamic creation of layers by regular expression (not need declare each module)
- Run along with the rest of tests from [phpunit](https://github.com/sebastianbergmann/phpunit)
- Asserts to method arguments and return types (for check dependent injection)
- Asserts to properties visibility
