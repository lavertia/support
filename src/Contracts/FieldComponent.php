<?php

declare(strict_types=1);

namespace Lavertia\Support\Contracts;

interface FieldComponent extends Component
{
    public function getFieldName(): string;
}
