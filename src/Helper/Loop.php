<?php

declare(strict_types=1);

namespace Mosaic\Helper;

use Mosaic\Exception\RenderException;
use Mosaic\Renderable;
use Mosaic\Renderer;
use Throwable;

/**
 * @internal
 */
final class Loop implements Renderable
{
    /** @var mixed */
    private mixed $view;

    /** @var iterable<int, mixed> */
    private iterable $items;

    /**
     * @param mixed $view
     * @param iterable<int, mixed> $items
     */
    public function __construct(mixed $view, iterable $items)
    {
        $this->view = $view;
        $this->items = $items;
    }

    /**
     * @SuppressWarnings PHPMD.UnusedFormalParameter
     *
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return iterable<mixed, mixed>
     * @throws RenderException|Throwable
     */
    public function render(Renderer $renderer, mixed $data): iterable
    {
        foreach ($this->items as $item) {
            yield $renderer->render($this->view, $item);
        }
    }
}