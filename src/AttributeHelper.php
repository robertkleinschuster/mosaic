<?php

namespace Mosaic;

use Closure;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use ReflectionFunction;
use UnitEnum;

class AttributeHelper
{
    /**
     * @param mixed $view
     * @return object[]
     * @throws ReflectionException
     */
    public function getAttributes(mixed $view): array
    {
        $attributes = [];

        if ($view instanceof Closure) {
            $reflection = new ReflectionFunction($view);
            foreach ($reflection->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
        }

        if ($view instanceof Renderable) {
            $reflection = new ReflectionClass($view);
            foreach ($reflection->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
            foreach ($reflection->getMethod('render')->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
        }

        if ($view instanceof RenderableEnum && $view instanceof UnitEnum) {
            $reflection = new ReflectionEnum($view);
            foreach ($reflection->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
            foreach ($reflection->getCase($view->name)->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
        }

        return $attributes;
    }
}