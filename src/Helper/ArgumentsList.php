<?php

declare(strict_types=1);

namespace Mosaic\Helper;

use Closure;
use Generator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<int, Arguments>
 */
class ArgumentsList implements IteratorAggregate
{
    /**
     * @var iterable<int, mixed>
     */
    private iterable $list;
    private Closure $callback;

    /**
     * @param iterable<int, mixed> $list
     * @param Closure|null $callback
     */
    public function __construct(iterable $list, ?Closure $callback = null)
    {
        $this->list = $list;
        $this->callback = $callback ?? fn($item) => new Arguments($item);
    }

    public function getIterator(): Generator
    {
        foreach ($this->list as $item) {
            yield ($this->callback)($item);
        }
    }
}