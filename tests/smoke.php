<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefix = 'Lavertia\\Support\\';

    if (! str_starts_with($class, $prefix)) {
        return;
    }

    $file = __DIR__.'/../src/'.str_replace('\\', '/', substr($class, strlen($prefix))).'.php';

    if (is_file($file)) {
        require $file;
    }
});

use Lavertia\Support\Components\Card;
use Lavertia\Support\Components\Column;
use Lavertia\Support\Components\Text;

$tree = Column::make([
    Card::make([Text::make('OK')->key('heading')])->key('card'),
])->toArray();

assert($tree['components'][0]['components'][0]['key'] === 'root/card/heading');
assert($tree['components'][0]['components'][0]['props']->content === 'OK');

echo "Lavertia Support smoke test passed\n";
