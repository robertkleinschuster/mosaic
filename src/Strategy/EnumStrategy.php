<?php

declare(strict_types=1);

namespace Mosaic\Strategy;

use BackedEnum;
use Mosaic\Exception\RenderException;
use Mosaic\FragmentCollection;
use Mosaic\RenderableBackedEnum;
use Mosaic\RenderableEnum;
use Mosaic\Renderer;
use Mosaic\Strategy\Base\PipelineStrategy;
use UnitEnum;

final class EnumStrategy extends PipelineStrategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return FragmentCollection
     * @throws RenderException
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        if ($view instanceof RenderableEnum && $view instanceof UnitEnum) {
            $result = '';

            if ($view instanceof RenderableBackedEnum && $view instanceof BackedEnum) {
                $result = $view->value;
            }

            return (new StringStrategy($renderer))->execute($result, $renderer, $data);
        }
        return $this->next($view, $renderer, $data);
    }
}