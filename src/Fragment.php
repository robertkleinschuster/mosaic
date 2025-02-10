<?php

declare(strict_types=1);

namespace Mosaic;

use Stringable;

final readonly class Fragment implements Stringable
{
    private array $args;
    public function __construct(public string|Stringable $content, string ...$args)
    {
        $this->args = $args;
    }

    public function __toString(): string
    {
        if (!empty($this->args)) {
            $args = [];
            foreach ($this->args as $key => $value) {
                $args['{' . $key . '}'] = $value;
            }
            return strtr($this->content, $args);
        }
        return (string)$this->content;
    }
}