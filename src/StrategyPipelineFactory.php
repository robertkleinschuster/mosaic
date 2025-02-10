<?php

namespace Mosaic;

use Mosaic\Strategy\AttributeStrategy;
use Mosaic\Strategy\CaptureStrategy;
use Mosaic\Strategy\ClosureStrategy;
use Mosaic\Strategy\EnumStrategy;
use Mosaic\Strategy\Factory\PipelineStrategyFactory;
use Mosaic\Strategy\FragmentStrategy;
use Mosaic\Strategy\InvalidViewStrategy;
use Mosaic\Strategy\IterableStrategy;
use Mosaic\Strategy\RenderableStrategy;
use Mosaic\Strategy\StringStrategy;

class StrategyPipelineFactory
{
    public function create(StrategyFactory ...$factories): StrategyPipeline
    {
        $factories[] = new PipelineStrategyFactory(CaptureStrategy::class);
        $factories[] = new PipelineStrategyFactory(AttributeStrategy::class);
        return new StrategyPipeline(
            new InvalidViewStrategy(),
            new PipelineStrategyFactory(RenderableStrategy::class),
            new PipelineStrategyFactory(ClosureStrategy::class),
            new PipelineStrategyFactory(IterableStrategy::class),
            new PipelineStrategyFactory(EnumStrategy::class),
            new PipelineStrategyFactory(StringStrategy::class),
            new PipelineStrategyFactory(FragmentStrategy::class),
            ...$factories,
        );
    }
}