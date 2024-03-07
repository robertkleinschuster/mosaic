<?php

namespace MosaicTest\Helper;

use Mosaic\Exception\RenderException;
use Mosaic\Helper\Conditional;
use Mosaic\Renderer;
use PHPUnit\Framework\TestCase;

class ConditionalTest extends TestCase
{
    /**
     * @throws RenderException
     */
    public function testShouldOnlyRenderWhenPredicateEvaluatesToTrue(): void
    {
        $conditional = new Conditional('test', fn() => true);
        $result = $conditional->render(new Renderer(), null);
        self::assertRender($result);
        $conditional = new Conditional('test', fn() => false);
        $result = $conditional->render(new Renderer(), null);
        self::assertNoRender($result);
        $conditional = new Conditional('test', fn() => null);
        $result = $conditional->render(new Renderer(), null);
        self::assertNoRender($result);
        $conditional = new Conditional('test', fn() => 1);
        $result = $conditional->render(new Renderer(), null);
        self::assertNoRender($result);

    }

    /**
     * @throws RenderException
     */
    public function testShouldPassDataToPredicate(): void
    {
        $conditional = new Conditional('test', fn($data) => 1 === $data);
        $result = $conditional->render(new Renderer(), null);
        self::assertNoRender($result);
        $result = $conditional->render(new Renderer(), 1);
        self::assertRender($result);
    }

    /**
     * @param iterable<int, mixed> $result
     * @return void
     */
    private static function assertRender(iterable $result): void
    {
        $items = [];
        foreach ($result as $item) {
            $items[] = $item;
        }
        self::assertNotEmpty($items);
    }

    /**
     * @param iterable<int, mixed> $result
     * @return void
     */
    private static function assertNoRender(iterable $result): void
    {
        $items = [];
        foreach ($result as $item) {
            $items[] = $item;
        }
        self::assertEmpty($items);
    }
}
