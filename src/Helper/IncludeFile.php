<?php

declare(strict_types=1);

namespace Mosaic\Helper;

use Mosaic\Renderable;
use Mosaic\Renderer;

readonly class IncludeFile implements Renderable
{
    public function __construct(private string $file)
    {
    }

    /**
     * @param Renderer $renderer
     * @param mixed $data
     * @return iterable<mixed>
     */
    public function render(Renderer $renderer, mixed $data): iterable
    {
        yield $data ?? 0 => require $this->file;
    }
}