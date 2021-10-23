# PHPUnit Application Architecture Test

Idea: write architecture tests as well as feature and unit tests

## Installation

### Install via composer

```bash
composer required-dev ta-tikoma/phpunit-architecture-tests
```

### Add trait to Test class

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
        $tests = LayerBuilder::fromNamespace('tests\\');

        $this->assertDoesNotDependOn($app, $tests);
        $this->assertDependOn($tests, $app);
    }

```

## Alternatives
[Deptrac](https://github.com/qossmic/deptrac)
[PHP Architecture Tester](https://github.com/carlosas/phpat)
