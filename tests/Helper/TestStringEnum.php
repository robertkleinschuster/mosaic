<?php

namespace MosaicTest\Helper;

use Mosaic\RenderableBackedEnum;

#[PrefixAttribute('enum')]
enum TestStringEnum: string implements RenderableBackedEnum
{
    #[OtherAttribute]
    case other = 'other value';
    #[OuterAttribute]
    case outer = 'outer value';
}
