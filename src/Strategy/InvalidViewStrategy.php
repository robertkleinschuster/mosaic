<?php

declare(strict_types=1);

namespace Mosaic\Strategy;

use Mosaic\Exception\RenderException;
use Mosaic\FragmentCollection;
use Mosaic\Renderer;
use Mosaic\Strategy;

final class InvalidViewStrategy implements Strategy
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return FragmentCollection
     * @throws RenderException
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        throw RenderException::forInvalidView($view);
    }
}