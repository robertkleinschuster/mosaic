<?php

namespace MosaicTest;

use Attribute;
use Mosaic\RenderableAttribute;
use Mosaic\Renderer;

#[Attribute]
#[NestedRenderableAttributeLevel1('level 1 ')]
class NestedRenderableAttribute implements RenderableAttribute
{
    public function __construct(private string $content)
    {
    }

    public function render(Renderer $renderer, mixed $children, mixed $data): mixed
    {
        return $this->content;
    }

}