<?php

declare(strict_types=1);

namespace Lavertia\Support\Components;

use InvalidArgumentException;
use Lavertia\Support\Contracts\Component;
use Lavertia\Support\Serialization\ComponentSerializer;

abstract class AbstractComponent implements Component
{
    protected ?string $key = null;

    /** @var array<string, mixed> */
    protected array $props = [];

    public function key(string $key): static
    {
        if (preg_match('/^[A-Za-z][A-Za-z0-9_.-]{0,127}$/D', $key) !== 1) {
            throw new InvalidArgumentException('Invalid component key.');
        }

        $copy = clone $this;
        $copy->key = $key;

        return $copy;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    /** @return array<string, mixed> */
    public function getProps(): array
    {
        return $this->props;
    }

    /** @return list<Component> */
    public function getComponents(): array
    {
        return [];
    }

    protected function withProp(string $name, mixed $value): static
    {
        if (preg_match('/^[A-Za-z][A-Za-z0-9_]*$/D', $name) !== 1) {
            throw new InvalidArgumentException('Invalid component property name.');
        }

        $copy = clone $this;
        $copy->props[$name] = $value;

        return $copy;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return (new ComponentSerializer())->serialize($this);
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
