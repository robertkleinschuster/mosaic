<?php

namespace MosaicTest\Helper;

use Mosaic\Renderable;
use Mosaic\Renderer;

#[PrefixAttribute('prefix')]
class RenderableWithAttribute implements Renderable
{
    public function render(Renderer $renderer, mixed $data)
    {
        yield 'renderable';
    }
}