<?php

declare(strict_types=1);

namespace Lavertia\Support\Contracts;

interface FormComponent extends Component
{
    public function getFormName(): string;
}
