<?php

declare(strict_types=1);

namespace Mosaic;

interface Strategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return FragmentCollection
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection;
}