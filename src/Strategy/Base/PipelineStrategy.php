<?php

declare(strict_types=1);

namespace Mosaic\Strategy\Base;

use Mosaic\FragmentCollection;
use Mosaic\Renderer;
use Mosaic\Strategy;

abstract class PipelineStrategy implements Strategy
{
    private Strategy $strategy;

    /**
     * @param Strategy $strategy
     */
    final public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
        $this->init();
    }

    protected function init(): void
    {
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param null|mixed $data
     * @return FragmentCollection
     */
    protected function next(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        return $this->strategy->execute($view, $renderer, $data);
    }
}