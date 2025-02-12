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
     * @template T
     * @param mixed $view
     * @param class-string<T>|null $name
     * @return T[]
     * @throws ReflectionException
     */
    public function getAttributes(mixed $view, string $name = null): array
    {
        $attributes = [];

        if ($view instanceof Closure) {
            $reflection = new ReflectionFunction($view);
            foreach ($reflection->getAttributes($name) as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
        }

        if ($view instanceof Renderable) {
            $reflection = new ReflectionClass($view);
            foreach ($reflection->getAttributes($name) as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
            foreach ($reflection->getMethod('render')->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
        }

        if ($view instanceof RenderableEnum && $view instanceof UnitEnum) {
            $reflection = new ReflectionEnum($view);
            foreach ($reflection->getAttributes($name) as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
            foreach ($reflection->getCase($view->name)->getAttributes($name) as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
        }

        return $attributes;
    }
}