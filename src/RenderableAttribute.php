<?php

declare(strict_types=1);

namespace Mosaic;

interface RenderableAttribute
{
    public function render(Renderer $renderer, mixed $children, mixed $data);
}
