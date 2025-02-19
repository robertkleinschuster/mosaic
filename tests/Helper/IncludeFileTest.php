<?php

declare(strict_types=1);

namespace Robts\Component\RendererTest\Helper;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Mosaic\Helper\IncludeFile;
use Mosaic\Renderer;

class IncludeFileTest extends TestCase
{
    #[Test]
    public function shouldRenderReturnValueOFFile()
    {
        $renderer = new Renderer();

        $result = $renderer->render(new IncludeFile(__DIR__ . '/renderable.php'));
        self::assertEquals('Hello world!', $result);
    }
}