<?php

declare(strict_types=1);

namespace Lavertia\Support\Tests;

use InvalidArgumentException;
use Lavertia\Support\Components\AbstractComponent;
use Lavertia\Support\Components\Card;
use Lavertia\Support\Components\Column;
use Lavertia\Support\Components\Text;
use Lavertia\Support\Serialization\ComponentProtocol;
use PHPUnit\Framework\TestCase;

final class ComponentProtocolV1Test extends TestCase
{
    public function test_component_protocol_v1_matches_the_committed_golden_fixture(): void
    {
        $root = Column::make([
            Text::make('Hello')->key('heading'),
            Card::make([Text::make('World')])->key('details'),
        ]);

        $encoded = json_encode(ComponentProtocol::from($root), JSON_THROW_ON_ERROR);
        $actual = json_decode($encoded, true, 512, JSON_THROW_ON_ERROR);
        $expected = json_decode(
            (string) file_get_contents(__DIR__.'/fixtures/protocol-v1.json'),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertSame($expected, $actual);
        self::assertSame(ComponentProtocol::VERSION, $actual['version']);
    }

    public function test_cyclic_component_graph_is_rejected(): void
    {
        $component = new class extends AbstractComponent
        {
            public function getType(): string
            {
                return 'loop';
            }

            public function getComponents(): array
            {
                return [$this];
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cyclic component tree detected');

        ComponentProtocol::from($component);
    }

    public function test_the_node_limit_is_enforced(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Component tree exceeds Lavertia limits');

        ComponentProtocol::from(Column::make(array_fill(0, 1000, Text::make('child'))));
    }

    public function test_nested_component_depth_is_limited(): void
    {
        $component = Text::make('leaf');

        for ($depth = 0; $depth < 34; $depth++) {
            $component = Column::make([$component]);
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Component tree exceeds Lavertia limits');

        ComponentProtocol::from($component);
    }

    public function test_non_finite_numbers_cannot_cross_the_protocol(): void
    {
        $component = new class extends AbstractComponent
        {
            public function getType(): string
            {
                return 'unsafe';
            }

            public function data(mixed $value): static
            {
                return $this->withProp('data', $value);
            }
        };

        $this->expectException(InvalidArgumentException::class);

        ComponentProtocol::from($component->data(['values' => [INF]]));
    }
}
