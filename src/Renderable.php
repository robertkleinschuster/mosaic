<?php
/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace Mosaic;

interface Renderable
{
    /**
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return iterable<mixed, mixed>
     */
    public function render(Renderer $renderer, mixed $data = null): iterable;
}