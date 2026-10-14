<?php

declare(strict_types=1);

namespace Lavertia\Support\Components;

final class Text extends AbstractComponent
{
    public static function make(string $text): static
    {
        return (new self)->withProp('content', $text);
    }

    public function getType(): string
    {
        return 'text';
    }
}
