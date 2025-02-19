<?php

declare(strict_types=1);

namespace MosaicTest\Helper;

use Attribute;
use Mosaic\RenderableAttribute;
use Mosaic\Renderer;

#[Attribute]
class PrefixAttribute implements RenderableAttribute
{
    public function __construct(private string $prefix)
    {
    }

    public function render(Renderer $renderer, mixed $children, mixed $data): mixed
    {
        yield $this->prefix;
        yield ' ';
        yield $children;
    }
}