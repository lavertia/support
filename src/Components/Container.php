<?php

declare(strict_types=1);

namespace Lavertia\Support\Components;

use Lavertia\Support\Contracts\Component;
use Lavertia\Support\Contracts\ComposesComponents;

class Container extends AbstractComponent implements ComposesComponents
{
    use SupportsComponents;

    /** @param list<Component> $components */
    public static function make(array $components = []): static
    {
        return (new static())->components($components);
    }

    public function getType(): string
    {
        return 'container';
    }
