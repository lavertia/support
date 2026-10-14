<?php

declare(strict_types=1);

namespace Lavertia\Support\Tests;

use Closure;
use InvalidArgumentException;
use Lavertia\Support\Components\AbstractComponent;
use Lavertia\Support\Components\Card;
use Lavertia\Support\Components\Column;
use Lavertia\Support\Components\Text;
use PHPUnit\Framework\TestCase;

final class ComponentSerializerTest extends TestCase
{
    public function test_nested_composition_has_stable_keys(): void
    {
        $node = Column::make([
            Card::make([Text::make('Hello')->key('title')])->key('profile'),
        ])->toArray();

        self::assertSame('root/profile/title', $node['components'][0]['components'][0]['key']);
        self::assertSame('Hello', $node['components'][0]['components'][0]['props']->content);
        self::assertJson(json_encode($node, JSON_THROW_ON_ERROR));
    }

    public function test_duplicate_sibling_keys_are_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Column::make([
            Text::make('One')->key('duplicate'),
            Text::make('Two')->key('duplicate'),
        ])->toArray();
    }

    public function test_php_closures_cannot_be_serialized(): void
    {
        $unsafe = new class extends AbstractComponent {
            public function getType(): string
            {
                return 'unsafe';
            }

            public function callback(Closure $callback): static
            {
                return $this->withProp('callback', $callback);
            }
        };

        $this->expectException(InvalidArgumentException::class);

        $unsafe->callback(static fn () => true)->toArray();
    }

    public function test_builders_are_immutable(): void
    {
        $original = Text::make('Original');
        $modified = $original->key('changed');

        self::assertNull($original->getKey());
        self::assertSame('changed', $modified->getKey());
    }
}
