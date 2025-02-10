<?php

declare(strict_types=1);

namespace MosaicTest\Strategy;

use Mosaic\Renderer;
use MosaicTest\Helper\OtherAttribute;
use MosaicTest\Helper\OuterAttribute;
use MosaicTest\Helper\RenderableWithAttribute;
use MosaicTest\Helper\RenderableWithMethodAttribute;
use MosaicTest\Helper\TestEnum;
use MosaicTest\Helper\TestStringEnum;
use MosaicTest\Helper\TestStringEnumUnbacked;
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

    public function testShouldRenderAttributesOnEnumCases()
    {
        $renderer = new Renderer();
        $result = $renderer->render(TestEnum::outer);
        $this->assertEquals('enum outer attribute ', (string)$result);
    }

    public function testShouldEnumValues()
    {
        $renderer = new Renderer();
        $result = $renderer->render(TestStringEnum::outer);
        $this->assertEquals('enum outer attribute outer value', (string)$result);
    }

    public function testShouldNotRenderEnumValuesForNonRenderableBackedEnum()
    {
        $renderer = new Renderer();
        $result = $renderer->render(TestStringEnumUnbacked::outer);
        $this->assertEquals('enum outer attribute ', (string)$result);
    }
}
