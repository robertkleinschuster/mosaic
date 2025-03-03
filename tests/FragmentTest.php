<?php

namespace MosaicTest;

use Mosaic\Fragment;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FragmentTest extends TestCase
{
    #[Test]
    public function shouldReplacePlaceholders()
    {
        $fragment = new Fragment('Hello {who}! My name is {name}.', who: 'world', name: 'Bob');
        self::assertSame('Hello world! My name is Bob.', (string) $fragment);
    }

    #[Test]
    public function shouldEncodePlaceholderReplacements()
    {
        $fragment = new Fragment('Hello {who}! My name is {name}.', who: 'world', name: '"Bob"');
        self::assertSame('Hello world! My name is &quot;Bob&quot;.', (string) $fragment);
    }
}
