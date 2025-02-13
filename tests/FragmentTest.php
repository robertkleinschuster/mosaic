<?php

namespace MosaicTest;

use Mosaic\Fragment;
use PHPUnit\Framework\TestCase;

class FragmentTest extends TestCase
{
    public function testShouldReplacePlaceholders()
    {
        $fragment = new Fragment('Hello {who}! My name is {name}.', who: 'world', name: 'Bob');
        self::assertSame('Hello world! My name is Bob.', (string) $fragment);
    }

    public function testShouldEncodePlaceholderReplacements()
    {
        $fragment = new Fragment('Hello {who}! My name is {name}.', who: 'world', name: '"Bob"');
        self::assertSame('Hello world! My name is &quot;Bob&quot;.', (string) $fragment);
    }
}
