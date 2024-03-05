<?php

declare(strict_types=1);

namespace Mosaic;

final class StrategyPipeline implements Strategy
{
    private Strategy $strategy;

    /**
     * @param Strategy $strategy
     * @param StrategyFactory ...$factories
     */
    public function __construct(Strategy $strategy, StrategyFactory ...$factories)
    {
        $this->strategy = $strategy;

        foreach ($factories as $factory) {
            $this->strategy = $factory->create($this->strategy);
        }
    }


    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return FragmentCollection
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        return $this->strategy->execute($view, $renderer, $data);
    }
}