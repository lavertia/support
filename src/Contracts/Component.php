<?php

declare(strict_types=1);

namespace Lavertia\Support\Contracts;

use JsonSerializable;

interface Component extends JsonSerializable
{
    public function getType(): string;

    public function getKey(): ?string;

    /** @return array<string, mixed> */
    public function getProps(): array;

    /** @return list<Component> */
    public function getComponents(): array;

    /** @return array<string, mixed> */
    public function toArray(): array;
}
