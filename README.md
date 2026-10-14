# Lavertia Support

A small, framework-agnostic PHP foundation for [Lavertia](https://github.com/lavertia/lavertia).

Build **composable UI trees in PHP** and serialize them into the same versioned JSON protocol for React, Vue and Svelte. This package has no dependency on Laravel, Inertia or Livewire.

```php
use Lavertia\Support\Components\{Card, Column, Text};
use Lavertia\Support\Serialization\ComponentProtocol;

$root = Column::make([
    Card::make([Text::make('Hello Lavertia')])->key('welcome'),
]);

$payload = ComponentProtocol::from($root);
```

## Design rules

- Every UI element implements `Component`. Typed contracts express component-specific capabilities.
- Containers compose children in any order, without requiring a Resource.
- Builder methods return immutable copies; sibling keys must be unique.
- The serializer rejects closures, arbitrary PHP objects, non-finite floats, cycles and oversized trees.
- All persistence, authorization and tenant scoping stay on the Laravel server.

## PHP and tests

Requires PHP 8.3+. Run `composer install`, then `composer test`. You can run `composer test:smoke` without a PHPUnit bootstrap.

This package is **experimental** (v0.1). See [panels](https://github.com/lavertia/panels) for Laravel integration.
