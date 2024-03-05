<?php

declare(strict_types=1);

namespace Mosaic;

use ArrayIterator;
use IteratorAggregate;
use Stringable;
use Traversable;

/**
 * @implements IteratorAggregate<int, Fragment>
 */
final class FragmentCollection implements Stringable, IteratorAggregate
{
    /** @var Fragment[]  */
    private array $fragments;

    public function __construct(Fragment ...$fragments)
    {
        $this->fragments = $fragments;
    }

    public function push(Fragment ...$fragments): self
    {
        array_push($this->fragments, ...$fragments);
        return $this;
    }

    public function pushCollection(FragmentCollection $collection): self
    {
        $this->push(...$collection->fragments);
        return $this;
    }

    /**
     * @return Traversable<int, Fragment>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->fragments);
    }

    public function __toString(): string
    {
        $result = '';
        foreach ($this->fragments as $fragment) {
            $result .= $fragment->__toString();
        }
        return $result;
    }
}