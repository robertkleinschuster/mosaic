<?php

declare(strict_types=1);

namespace MosaicTest\Strategy\Factory;

use Mosaic\Exception\StrategyFactoryException;
use Mosaic\Strategy\Factory\PipelineStrategyFactory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

class PipelineStrategyFactoryTest extends TestCase
{
    #[Test]
    public function shouldThrowExceptionForInvalidClass(): void
    {
        self::expectException(StrategyFactoryException::class);
        // @phpstan-ignore-next-line
        new PipelineStrategyFactory(stdClass::class);
    }
}
