<?php

declare(strict_types=1);

namespace Lavertia\Support\Contracts;

interface TableColumnComponent extends Component
{
    public function getColumnName(): string;
}
