<?php

declare(strict_types=1);

namespace Lavertia\Support\Serialization;

use InvalidArgumentException;
use Lavertia\Support\Contracts\Component;
use SplObjectStorage;

final class ComponentSerializer
{
    private const MAX_NODES = 1000;
    private const MAX_DEPTH = 32;
    private const MAX_PROP_DEPTH = 16;

    /** @return array<string, mixed> */
    public function serialize(Component $component): array
    {
        $active = new SplObjectStorage();
        $nodeCount = 0;

        return $this->serializeNode($component, 'root', 0, $nodeCount, $active);
    }

    /** @return array<string, mixed> */
    private function serializeNode(
        Component $component,
        string $path,
        int $depth,
        int &$nodeCount,
        SplObjectStorage $active,
    ): array {
        if ($depth > self::MAX_DEPTH || ++$nodeCount > self::MAX_NODES) {
            throw new InvalidArgumentException('Component tree exceeds Lavertia limits.');
        }

        if ($active->contains($component)) {
            throw new InvalidArgumentException('Cyclic component tree detected.');
        }

        if (preg_match('/^[a-z][a-z0-9-]*$/D', $component->getType()) !== 1) {
            throw new InvalidArgumentException('Invalid component type.');
        }

        $active->attach($component);

        try {
            $components = $component->getComponents();

            if (! array_is_list($components)) {
                throw new InvalidArgumentException('Component children must be a list.');
            }

            $children = [];
            $siblingKeys = [];

            foreach ($components as $index => $child) {
                if (! $child instanceof Component) {
                    throw new InvalidArgumentException('Invalid component child.');
                }

                $localKey = $child->getKey() ?? $child->getType().'-'.$index;

                if (preg_match('/^[A-Za-z][A-Za-z0-9_.-]{0,127}$/D', $localKey) !== 1) {
                    throw new InvalidArgumentException('Invalid component key.');
                }

                if (isset($siblingKeys[$localKey])) {
                    throw new InvalidArgumentException('Duplicate sibling component key: '.$localKey);
                }

                $siblingKeys[$localKey] = true;
                $children[] = $this->serializeNode($child, $path.'/'.$localKey, $depth + 1, $nodeCount, $active);
            }

            return [
                'type' => $component->getType(),
                'key' => $path,
                'props' => (object) $this->normalizeProperties($component->getProps()),
                'components' => $children,
            ];
        } finally {
            $active->detach($component);
        }
    }

    /** @param array<string, mixed> $props
     *  @return array<string, mixed>
     */
    private function normalizeProperties(array $props): array
    {
        if ($props !== [] && array_is_list($props)) {
            throw new InvalidArgumentException('Component props must be a map.');
        }

        $normalized = [];

        foreach ($props as $key => $value) {
            if (! is_string($key) || preg_match('/^[A-Za-z][A-Za-z0-9_]*$/D', $key) !== 1) {
                throw new InvalidArgumentException('Invalid component property name.');
            }

            $normalized[$key] = $this->normalize($value, 0);
        }

        return $normalized;
    }

    private function normalize(mixed $value, int $depth): mixed
    {
        if ($depth > self::MAX_PROP_DEPTH) {
            throw new InvalidArgumentException('Component props exceed nesting limits.');
        }

        if ($value === null || is_bool($value) || is_int($value) || is_string($value)) {
            return $value;
        }

        if (is_float($value) && is_finite($value)) {
            return $value;
        }

        if (is_array($value)) {
            $normalized = [];

            foreach ($value as $key => $item) {
                if (! is_int($key) && ! is_string($key)) {
                    throw new InvalidArgumentException('Invalid nested property key.');
                }

                $normalized[$key] = $this->normalize($item, $depth + 1);
            }

            return array_is_list($normalized) ? $normalized : (object) $normalized;
        }

        throw new InvalidArgumentException('Only JSON-safe scalars and arrays are allowed in component props.');
    }
}
