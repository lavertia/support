<?php

declare(strict_types=1);

namespace Lavertia\Support\Serialization;

use Lavertia\Support\Contracts\Component;

final class ComponentProtocol
{
    public const VERSION = 1;

    /** @return array{version: int, tree: array<string, mixed>} */
    public static function from(Component $component): array
    {
        return [
            'version' => self::VERSION,
            'tree' => (new ComponentSerializer)->serialize($component),
        ];
    }
}
