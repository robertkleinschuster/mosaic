<?php

declare(strict_types=1);

namespace Mosaic\Strategy;

use Mosaic\Fragment;
use Mosaic\FragmentCollection;
use Mosaic\Renderer;
use Mosaic\Strategy\Base\PipelineStrategy;

class FragmentStrategy extends PipelineStrategy
{
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        if ($view instanceof FragmentCollection) {
            return $view;
        }

        if ($view instanceof Fragment) {
            return new FragmentCollection($view);
        }

        return $this->next($view, $renderer, $data);
    }
}