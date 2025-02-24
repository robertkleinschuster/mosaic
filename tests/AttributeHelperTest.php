<?php

namespace MosaicTest;

use Mosaic\AttributeHelper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AttributeHelperTest extends TestCase
{
    #[Test]
    public function shouldExtractNestedRenderableAttributes()
    {
        $attributeHelper = new AttributeHelper();
        $renderable = #[NestedRenderableAttribute('nested ')] fn() => 'root ';
        $attributes = $attributeHelper->getAttributes($renderable);
        self::assertCount(3, $attributes);
    }
}
