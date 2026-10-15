<?php

declare(strict_types=1);

namespace Lavertia\Support\Contracts;

interface ActionComponent extends Component
{
    public function getActionName(): string;
}
