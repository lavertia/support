<?php

declare(strict_types=1);

namespace Lavertia\Support\Contracts;

interface InfolistComponent extends Component
{
    public function getInfolistName(): string;
}
