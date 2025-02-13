<?php

declare(strict_types=1);

namespace Mosaic\Strategy;

use Mosaic\Fragment;
use Mosaic\FragmentCollection;
use Mosaic\Renderer;
use Mosaic\Strategy\Base\PipelineStrategy;

final class StringStrategy extends PipelineStrategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return FragmentCollection
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        if (is_string($view)) {
            return new FragmentCollection(new Fragment(htmlspecialchars($view, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8', false)));
        }
        return $this->next($view, $renderer, $data);
    }
}