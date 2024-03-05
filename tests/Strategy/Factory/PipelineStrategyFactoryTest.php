<?php

declare(strict_types=1);

namespace MosaicTest\Strategy\Factory;

use Mosaic\Exception\StrategyFactoryException;
use Mosaic\Strategy\Factory\PipelineStrategyFactory;
use PHPUnit\Framework\TestCase;
use stdClass;

class PipelineStrategyFactoryTest extends TestCase
{
    public function testShouldThrowExceptionForInvalidClass(): void
    {
        self::expectException(StrategyFactoryException::class);
        // @phpstan-ignore-next-line
        new PipelineStrategyFactory(stdClass::class);
    }
}
