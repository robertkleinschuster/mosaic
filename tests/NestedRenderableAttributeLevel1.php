<?php

namespace MosaicTest;

use Attribute;
use Mosaic\RenderableAttribute;

#[Attribute]
#[NestedRenderableAttributeLevel2('level 2 ')]
class NestedRenderableAttributeLevel1 implements RenderableAttribute
{
    public function __construct(private string $content)
    {
    }

    public function render(\Mosaic\Renderer $renderer, mixed $children, mixed $data): mixed
    {
        return $this->content;
    }

}