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
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AttributeStrategyTest extends TestCase
{
    #[Test]
    public function shouldWrapClosuresAndRenderablesInRenderableAttributes()
    {
        $view = #[OuterAttribute] #[OtherAttribute] fn() => 'inner view';

        $renderer = new Renderer();
        $result = $renderer->render($view);
        $this->assertEquals('outer attribute other attribute inner view', (string)$result);
    }

    #[Test]
    public function shouldRenderAttributesOnRenderable()
    {
        $view = new RenderableWithAttribute();
        $renderer = new Renderer();
        $result = $renderer->render($view);
        $this->assertEquals('prefix renderable', (string)$result);
    }

    #[Test]
    public function shouldRenderAttributesOnRenderMethod()
    {
        $view = new RenderableWithMethodAttribute();
        $renderer = new Renderer();
        $result = $renderer->render($view);
        $this->assertEquals('prefix renderable', (string)$result);
    }

    #[Test]
    public function shouldRenderAttributesOnEnumCases()
    {
        $renderer = new Renderer();
        $result = $renderer->render(TestEnum::outer);
        $this->assertEquals('enum outer attribute ', (string)$result);
    }

    #[Test]
    public function shouldRenderEnumValues()
    {
        $renderer = new Renderer();
        $result = $renderer->render(TestStringEnum::outer);
        $this->assertEquals('enum outer attribute outer value', (string)$result);
    }

    #[Test]
    public function shouldNotRenderEnumValuesForNonRenderableBackedEnum()
    {
        $renderer = new Renderer();
        $result = $renderer->render(TestStringEnumUnbacked::outer);
        $this->assertEquals('enum outer attribute ', (string)$result);
    }
}
