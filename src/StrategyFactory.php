<?php

declare(strict_types=1);

namespace Mosaic;

interface StrategyFactory
{
    public function create(Strategy $next): Strategy;
}