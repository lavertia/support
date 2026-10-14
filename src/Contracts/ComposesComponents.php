<?php

declare(strict_types=1);

namespace Lavertia\Support\Contracts;

interface ComposesComponents extends Component
{
    /** @param list<Component> $components */
    public function components(array $components): static;
}
