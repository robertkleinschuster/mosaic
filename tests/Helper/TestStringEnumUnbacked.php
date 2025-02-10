<?php

namespace MosaicTest\Helper;

use Mosaic\RenderableEnum;

#[PrefixAttribute('enum')]
enum TestStringEnumUnbacked: string implements RenderableEnum
{
    #[OtherAttribute]
    case other = 'other value';
    #[OuterAttribute]
    case outer = 'outer value';
}
