# Component Protocol v1 - frozen compatibility fixture

> Development-phase contract, not a semver-stable Packagist release.

Lavertia Support turns immutable PHP Components into a frontend-neutral JSON tree. React 19, Vue 3 and Svelte 5 adapters must interpret the **same** serialized shape, never execute PHP or receive authorization callbacks.

## Envelope

```json
{
  "version": 1,
  "tree": {
    "type": "text",
    "key": "root",
    "props": {"content": "Hello"},
    "components": []
  }
}
```

The root `key` is `root`. Descendants include stable, validated sibling keys in their path. Each serialized node has exactly `type`, `key`, `props` and `components`. Empty props encode as `{}`, not `[]`.

### Safety invariants

- Only scalar JSON values and nested arrays/maps are allowed in props. No closures, models, objects, resources, non-finite floats or PHP code cross the wire.
- Sibling keys must be unique. Component cycles are rejected.
- The serializer rejects more than 1,000 nodes, depth greater than 32, or property nesting deeper than 16.
- An adapter must **reject unknown major protocol versions** rather than silently guessing compatibility.
- Per-request access checks, Eloquent scopes and state mutations stay in Laravel. Hiding a column or button is **not** authorization.

`schema/component-protocol-v1.schema.json` validates portable syntax. The serializer enforces additional dynamic constraints that are not expressed in JSON Schema (global node count, cycles, sibling uniqueness and max depth).

`tests/fixtures/protocol-v1.json` is a golden fixture, enforced by `ComponentProtocolV1Test`. Do not change it without an explicit protocol compatibility decision and matching frontend adapter tests.

The full Inertia page payload additionally contains page-local metadata such as `title`, `locale`, and `direction`; that page envelope belongs to `lavertia/panels` rather than this library.
