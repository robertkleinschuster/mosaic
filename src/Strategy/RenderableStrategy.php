<?php
/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace Mosaic\Strategy;

use Mosaic\Exception\RenderException;
use Mosaic\FragmentCollection;
use Mosaic\Renderable;
use Mosaic\Renderer;
use Mosaic\Strategy\Base\PipelineStrategy;
use Throwable;

final class RenderableStrategy extends PipelineStrategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return FragmentCollection
     * @throws RenderException|Throwable
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        if ($view instanceof Renderable) {
            return (new IterableStrategy($renderer))->execute(
                $view->render($renderer, $data),
                $renderer,
                $data
            );
        }
        return $this->next($view, $renderer, $data);
    }
}