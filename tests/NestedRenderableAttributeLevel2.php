<?php

namespace MosaicTest;

use Attribute;
use Mosaic\RenderableAttribute;

#[Attribute]
class NestedRenderableAttributeLevel2 implements RenderableAttribute
{
    public function __construct(private string $content)
    {
    }

    public function render(\Mosaic\Renderer $renderer, mixed $children, mixed $data): mixed
    {
        return $this->content;
    }

}