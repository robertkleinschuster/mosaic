<?php

namespace MosaicTest\Helper;

use Mosaic\Exception\RenderException;
use Mosaic\Helper\ArgumentsList;
use Mosaic\Helper\Loop;
use Mosaic\Renderer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LoopTest extends TestCase
{
    /**
     * @throws RenderException
     */
    #[Test]
    public function shouldRenderViewForEachDataItem(): void
    {
        $presidents = [
            [
                'idNumber' => 1,
                'name' => 'Barack Obama',
            ],
            [
                'idNumber' => 2,
                'name' => 'Donald Trump',
            ],
            [
                'idNumber' => 3,
                'name' => 'Joe Biden',
            ],
        ];

        $view = fn(int $idNumber, string $name) => "$idNumber: $name\n";

        $loop = new Loop($view, new ArgumentsList($presidents));

        $result = '';
        /** @noinspection PhpLoopCanBeReplacedWithImplodeInspection */
        foreach ($loop->render(new Renderer(), null) as $item) {
            $result .= $item;
        }

        self::assertEquals("1: Barack Obama\n2: Donald Trump\n3: Joe Biden\n", $result);
    }
}
