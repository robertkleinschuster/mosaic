<?php

declare(strict_types=1);

namespace MosaicTest\Helper;

use Attribute;
use Mosaic\RenderableAttribute;
use Mosaic\Renderer;

#[Attribute]
class OtherAttribute implements RenderableAttribute
{
    public function render(Renderer $renderer, mixed $children, mixed $data)
    {
        yield 'other attribute';
        yield ' ';
        yield $children;
    }
}