<?php

declare(strict_types=1);

namespace MosaicTest\Strategy;

use Mosaic\Renderer;
use MosaicTest\Helper\OtherAttribute;
use MosaicTest\Helper\OuterAttribute;
use MosaicTest\Helper\RenderableWithAttribute;
use MosaicTest\Helper\RenderableWithMethodAttribute;
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

    public function testShouldRenderAttributesOnRenderable()
    {
        $view = new RenderableWithAttribute();
        $renderer = new Renderer();
        $result = $renderer->render($view);
        $this->assertEquals('prefix renderable', (string)$result);
    }

    public function testShouldRenderAttributesOnRenderMethod()
    {
        $view = new RenderableWithMethodAttribute();
        $renderer = new Renderer();
        $result = $renderer->render($view);
        $this->assertEquals('prefix renderable', (string)$result);
    }
}
