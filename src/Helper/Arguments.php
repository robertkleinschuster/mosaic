<?php

namespace Mosaic\Helper;

use ArrayObject;

/**
 * @internal
 * @extends ArrayObject<string, mixed>
 */
final class Arguments extends ArrayObject
{
    public static function from(mixed ...$args): self
    {
        return new Arguments($args);
    }
}