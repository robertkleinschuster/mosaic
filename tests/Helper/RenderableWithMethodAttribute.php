<?php

namespace MosaicTest\Helper;

use Mosaic\Renderable;
use Mosaic\Renderer;

class RenderableWithMethodAttribute implements Renderable
{
    #[PrefixAttribute('prefix')]
    public function render(Renderer $renderer, mixed $data)
    {
        yield 'renderable';
    }
}