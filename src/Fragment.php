<?php

declare(strict_types=1);

namespace Mosaic;

use Stringable;

final readonly class Fragment implements Stringable
{
    public function __construct(public string|Stringable $content)
    {
    }

    public function __toString(): string
    {
        return (string)$this->content;
    }
}