<?php

declare(strict_types=1);

namespace Lavertia\Support\Components;

final class Column extends Container
{
    public function getType(): string
    {
        return 'column';
    }
