<?php

declare(strict_types=1);

namespace Mosaic\Strategy;

use Closure;
use Mosaic\Exception\RenderException;
use Mosaic\FragmentCollection;
use Mosaic\Renderable;
use Mosaic\RenderableAttribute;
use Mosaic\Renderer;
use Mosaic\Strategy\Base\PipelineStrategy;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use Throwable;

class AttributeStrategy extends PipelineStrategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed $data
     * @return FragmentCollection
     * @throws ReflectionException
     * @throws RenderException
     * @throws Throwable
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
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


        if (!empty($attributes)) {
            $children = fn() => $this->next($view, $renderer, $data);
            foreach (array_reverse($attributes) as $attribute) {
                if ($attribute instanceof RenderableAttribute) {
                    $children = $renderer->render($attribute->render($renderer, $children, $data));
                }
            }
            return $this->next($children, $renderer, $data);
        }

        return $this->next($view, $renderer, $data);
    }
}
