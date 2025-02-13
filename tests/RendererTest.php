<?php

declare(strict_types=1);

namespace MosaicTest;

use Generator;
use MosaicTest\Helper\PrefixAttribute;
use PHPUnit\Framework\TestCase;
use Mosaic\Exception\RenderException;
use Mosaic\Fragment;
use Mosaic\Helper\Arguments;
use Mosaic\Helper\Capture;
use Mosaic\Helper\Placeholder;
use Mosaic\Renderable;
use Mosaic\Renderer;
use Mosaic\StrategyPipelineFactory;
use Throwable;

final class RendererTest extends TestCase
{
    private Renderer $renderer;

    public const HELLO_WORLD_STRING = 'Hello world!';
    public const HELLO_WORLD_ARRAY = ['Hello', ' ', 'world', '!'];

    protected function setUp(): void
    {
        parent::setUp();
        $strategyFactory = new StrategyPipelineFactory();
        $this->renderer = new Renderer(
            $strategyFactory->create(),
            256
        );
    }

    /**
     * @noinspection SpellCheckingInspection
     * @return Generator
     */
    public static function dataProvider_ViewTypes(): Generator
    {
        yield 'Should render string.' => [self::HELLO_WORLD_STRING];
        yield 'Should render array of strings.' => [self::HELLO_WORLD_ARRAY];
        yield 'Should render closure returning string.' => [fn() => self::HELLO_WORLD_STRING];
        yield 'Should render closure returning array strings.' => [fn() => self::HELLO_WORLD_ARRAY];
        yield 'Should render array of closures returning array of strings' => [
            [
                fn() => ['Hello', ' '],
                fn() => ['world', '!']
            ]
        ];
        yield 'Should render renderable objects.' => [
            new class implements Renderable {
                /**
                 * @param Renderer $renderer
                 * @param mixed|null $data
                 * @return string[]
                 */
                public function render(Renderer $renderer, mixed $data = null): array
                {
                    return RendererTest::HELLO_WORLD_ARRAY;
                }
            }
        ];
        yield 'Should render closure returning generator.' => [
            fn() => yield from self::HELLO_WORLD_ARRAY
        ];
    }

    /**
     * @dataProvider dataProvider_ViewTypes
     * @param mixed $view
     * @return void
     * @throws RenderException
     */
    public function testShouldRenderDifferentViewTypes($view): void
    {
        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    /**
     * @throws RenderException
     */
    public function testShouldThrowExceptionForInfiniteRenderLoops(): void
    {
        $nestingLevel = ini_get('xdebug.max_nesting_level');
        ini_set('xdebug.max_nesting_level', '5000');
        $view = new class implements Renderable {
            public function render(Renderer $renderer, mixed $data): Generator
            {
                yield new static();
            }
        };
        self::expectExceptionObject(
            RenderException::forMaxNestingLevel($view, $this->renderer->getMaxLevel())
        );
        $this->renderer->render($view);
        ini_set('xdebug.max_nesting_level', $nestingLevel);
    }

    /**
     * @throws RenderException
     */
    public function testShouldResetLevel(): void
    {
        self::assertEquals(0, $this->renderer->getLevel());
        $this->renderer->render(['', ['']]);
        self::assertEquals(0, $this->renderer->getLevel());
    }

    public function testShouldEscapeHtml()
    {
        self::assertEquals('&lt;test&gt;', (string)$this->renderer->render("<test>"));
    }

    /**
     * @throws RenderException
     */
    public function testShouldReplacePlaceholderWithCapture(): void
    {
        $view = [
            new Placeholder('replace'),
            [
                'world!',
                new Capture(new Placeholder('replace'), 'Hello ')
            ],
        ];

        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    /**
     * @throws RenderException
     */
    public function testShouldAppendToCapture(): void
    {
        $view = [
            new Placeholder('replace'),
            [
                new Capture(new Placeholder('replace'), 'Hello '),
                new Capture(new Placeholder('replace'), 'world!')
            ],
        ];

        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    /**
     * @throws RenderException
     */
    public function testShouldCaptureWithAttributes(): void
    {
        $view = [
            new Placeholder('replace'),
            [
                #[PrefixAttribute('Prefix')] fn() => new Capture(new Placeholder('replace'), 'Hello '),
                new Capture(new Placeholder('replace'), #[PrefixAttribute('world!')] fn() => ''),
            ],
        ];

        self::assertEquals(self::HELLO_WORLD_STRING . ' Prefix ', (string)$this->renderer->render($view));
    }

    /**
     * @throws RenderException
     */
    public function testShouldResetCaptures(): void
    {
        $view = [
            new Placeholder('replace'),
            [
                new Capture(new Placeholder('replace'), 'Hello '),
                new Capture(new Placeholder('replace'), 'world!')
            ],
        ];

        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));

        $view2 = [
            new Placeholder('replace'),
            [
                new Capture(new Placeholder('replace'), 'Hello '),
                new Capture(new Placeholder('replace'), 'world!')
            ],
        ];

        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view2));
    }


    /**
     * @throws RenderException
     */
    public function testShouldThrowExceptionWhenPlaceholderIsUsedMoreThenOnce(): void
    {
        self::expectExceptionObject(new RenderException('Placeholder "replace" already in use.'));
        $view = [
            new Placeholder('replace'),
            new Placeholder('replace')
        ];
        $this->renderer->render($view);
    }


    /**
     * @throws RenderException
     * @noinspection PhpParamsInspection
     * @noinspection SpellCheckingInspection
     */
    public function testShouldConvertThrowablesToRenderException(): void
    {
        // @phpstan-ignore-next-line
        $view = fn() => count(null);
        try {
            $x = $view();
            self::assertFalse($x);
        } catch (Throwable $throwable) {
            self::expectExceptionObject(RenderException::forThrowableInView($throwable, $view));
        }

        $this->renderer->render($view);
    }

    /**
     * @throws RenderException
     */
    public function testShouldRenderListOfItemsInLoop(): void
    {
        $view = fn(Renderer $renderer, array $items) => $renderer->foreach(
            $items,
            fn(Renderer $renderer, string $item) => new Fragment("<p>$item</p>")
        );
        self::assertEquals(
            '<p>foo</p><p>bar</p><p>baz</p>',
            $this->renderer->render($view, new Arguments(['items' => ['foo', 'bar', 'baz']]))
        );
    }

    /**
     * @throws RenderException
     */
    public function testShouldRenderConditionally(): void
    {
        $view = fn(Renderer $renderer, array $items) => $renderer->foreach(
            $items,
            fn(Renderer $renderer, string $item) => $renderer->if(
                fn($item) => $item === 'foo',
                new Fragment("<p>$item</p>"),
                $item
            )
        );
        self::assertEquals(
            '<p>foo</p>',
            $this->renderer->render($view, new Arguments(['items' => ['foo', 'bar', 'baz']]))
        );
    }

    /**
     * @throws RenderException
     */
    public function testShouldPassArgumentsToClosure(): void
    {
        $view = fn(string $name, string $greeting) => "$greeting $name!";
        self::assertEquals(
            self::HELLO_WORLD_STRING,
            $this->renderer->render($view, new Arguments(['greeting' => 'Hello', 'name' => 'world']))
        );
    }

    /**
     * @throws RenderException
     */
    public function testShouldPassIteratorKeyAsData(): void
    {
        $view = fn() => yield Arguments::from(name: 'world') => fn(string $name) => "Hello $name!";
        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    /**
     * @throws RenderException
     */
    public function testShouldPassIteratorKeyAsArgumentsToClosure(): void
    {
        $view = fn() => yield new Arguments(['greeting' => 'Hello', 'name' => 'world'])
        => fn(string $name, string $greeting) => "$greeting $name!";
        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    /**
     * @noinspection SpellCheckingInspection
     * @throws RenderException
     */
    public function testShouldRenderExamplePage(): void
    {
        $layout = fn(Renderer $r, string $title, string $language, $body) => new Fragment(<<<HTML
<html lang="$language">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>$title</title>
    <style>
        *, *:before, *:after {
            box-sizing: border-box;
        }
        html, body {
            padding: 0;
            margin: 0;
            font-family: sans-serif;
        }
        {$r->placeholder('css')}
    </style>
</head>
<body>
    {$r->render($body)}
</body>
</html>
HTML
        );

        $css = new Fragment(<<<CSS
p {
    padding: 1rem;
    border: 1px solid black;
}
CSS
        );


        $presidents = [
            [
                'id' => 1,
                'name' => 'Barack Obama',
            ],
            [
                'id' => 2,
                'name' => 'Donald Trump',
            ],
            [
                'id' => 3,
                'name' => 'Joe Biden',
            ],
        ];

        $personList = fn(Renderer $r) => new Fragment(<<<HTML
<ul>
    {$r->foreach($presidents, fn($data) => new Fragment("<li>{$data['id']}: {$data['name']}</li>"))}
</ul>
HTML
        );

        $body = fn(Renderer $r) => new Fragment(<<<HTML
{$r->capture('css', $css)}
<h1>Hello world!</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
{$r->render($personList)}
HTML
        );

        $result = (string)$this->renderer->render(
            $layout,
            new Arguments([
                'language' => 'en',
                'title' => 'Hello world!',
                'body' => $body,
            ])
        );

        $expected = <<<HTML
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hello world!</title>
    <style>
        *, *:before, *:after {
            box-sizing: border-box;
        }
        html, body {
            padding: 0;
            margin: 0;
            font-family: sans-serif;
        }
        p {
    padding: 1rem;
    border: 1px solid black;
}
    </style>
</head>
<body>
    
<h1>Hello world!</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
<ul>
    <li>1: Barack Obama</li><li>2: Donald Trump</li><li>3: Joe Biden</li>
</ul>
</body>
</html>
HTML;
        self::assertEquals($expected, $result);
    }


}
