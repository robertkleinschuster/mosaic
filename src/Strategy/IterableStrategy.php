<?php

declare(strict_types=1);

namespace Mosaic\Strategy;

use Mosaic\Exception\RenderException;
use Mosaic\FragmentCollection;
use Mosaic\Helper\Arguments;
use Mosaic\Renderer;
use Mosaic\Strategy\Base\PipelineStrategy;
use Throwable;

final class IterableStrategy extends PipelineStrategy
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
        if (is_iterable($view)) {
            $fragments = new FragmentCollection();
            foreach ($view as $itemData => $item) {
                if ($itemData instanceof Arguments) {
                    $data = $itemData;
                }
                $fragments->pushCollection($renderer->render($item, $data));
            }
            return $fragments;
        }
        return $this->next($view, $renderer, $data);
    }
}