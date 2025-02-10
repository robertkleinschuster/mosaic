<?php

namespace MosaicTest\Helper;

use Mosaic\RenderableEnum;

#[PrefixAttribute('enum')]
enum TestEnum implements RenderableEnum
{
    #[OtherAttribute]
    case other;
    #[OuterAttribute]
    case outer;
}
