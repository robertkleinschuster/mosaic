<?php

namespace Mosaic;

use Attribute;
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
        } else if ($view instanceof Renderable || $view instanceof RenderableAttribute) {
            $reflection = new ReflectionClass($view);
            foreach ($reflection->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
            foreach ($reflection->getMethod('render')->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
        } else if ($view instanceof RenderableEnum && $view instanceof UnitEnum) {
            $reflection = new ReflectionEnum($view);
            foreach ($reflection->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
            foreach ($reflection->getCase($view->name)->getAttributes() as $attribute) {
                $attributes[] = $attribute->newInstance();
            }
        }

        $nestedAttributes = [];
        foreach ($attributes as $attribute) {
            if ($attribute instanceof RenderableAttribute) {
                foreach ($this->getAttributes($attribute) as $nestedAttribute) {
                    if (!$nestedAttribute instanceof Attribute) {
                        $nestedAttributes[] = $nestedAttribute;
                    }
                }
            }
        }

        return [...$attributes, ...$nestedAttributes];
    }
}