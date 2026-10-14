<?php

declare(strict_types=1);

namespace Lavertia\Support\Components;

use InvalidArgumentException;
use Lavertia\Support\Contracts\Component;

trait SupportsComponents
{
    /** @var list<Component> */
    protected array $children = [];

    /** @param list<Component> $components */
    public function components(array $components): static
    {
        if (! array_is_list($components)) {
            throw new InvalidArgumentException('Component children must be a list.');
        }

        foreach ($components as $child) {
            if (! $child instanceof Component) {
                throw new InvalidArgumentException('All children must implement the Component contract.');
            }
        }

        $copy = clone $this;
        $copy->children = $components;

        return $copy;
    }

    /** @return list<Component> */
    public function getComponents(): array
    {
        return $this->children;
    }
