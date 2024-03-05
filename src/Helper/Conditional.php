<?php

declare(strict_types=1);

namespace Mosaic\Helper;

use Closure;
use Mosaic\Exception;
use Mosaic\Renderable;
use Mosaic\Renderer;
use Throwable;

/**
 * @internal
 */
final class Conditional implements Renderable
{
    /** @var mixed */
    private mixed $view;
    private Closure $predicate;

    /**
     * @param mixed $view
     * @param Closure $predicate
     */
    public function __construct(mixed $view, Closure $predicate)
    {
        $this->view = $view;
        $this->predicate = $predicate;
    }

    /**
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return iterable<mixed, mixed>
     * @throws Exception\RenderException|Throwable
     */
    public function render(Renderer $renderer, mixed $data = null): iterable
    {
        if (($this->predicate)($data) === true) {
            yield $renderer->render($this->view, $data);
        }
    }
}