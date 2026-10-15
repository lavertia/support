<?php

declare(strict_types=1);

namespace Lavertia\Support\Contracts;

interface InfolistEntryComponent extends Component
{
    public function getEntryName(): string;
}
