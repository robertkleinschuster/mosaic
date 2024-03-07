<?php

declare(strict_types=1);

namespace MosaicTest\Helper;

use Attribute;
use Mosaic\RenderableAttribute;
use Mosaic\Renderer;

#[Attribute]
class OuterAttribute implements RenderableAttribute
{
    public function render(Renderer $renderer, mixed $children, mixed $data)
    {
        yield 'outer attribute';
        yield ' ';
        yield $children;
    }
}