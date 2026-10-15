<?php

declare(strict_types=1);

namespace Lavertia\Support\Contracts;

interface TableComponent extends Component
{
    public function getTableName(): string;
}
