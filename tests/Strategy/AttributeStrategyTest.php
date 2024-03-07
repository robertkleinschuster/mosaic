<?php

declare(strict_types=1);

namespace MosaicTest\Strategy;

use Mosaic\Renderer;
use MosaicTest\Helper\OtherAttribute;
use MosaicTest\Helper\OuterAttribute;
use PHPUnit\Framework\TestCase;

class AttributeStrategyTest extends TestCase
{
    public function testShouldWrapClosuresAndRenderablesInRenderableAttributes()
    {
        $view = #[OuterAttribute] #[OtherAttribute] fn() => 'inner view';

        $renderer = new Renderer();
        $result = $renderer->render($view);
        $this->assertEquals('outer attribute other attribute inner view', (string)$result);
    }
}
