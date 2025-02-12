<?php

declare(strict_types=1);

namespace Mosaic\Strategy;

use Mosaic\AttributeHelper;
use Mosaic\Exception\RenderException;
use Mosaic\FragmentCollection;
use Mosaic\RenderableAttribute;
use Mosaic\Renderer;
use Mosaic\Strategy\Base\PipelineStrategy;
use ReflectionException;
use Throwable;

class AttributeStrategy extends PipelineStrategy
{
    private AttributeHelper $attributeHelper;

    protected function init(): void
    {
        parent::init();
        $this->attributeHelper = new AttributeHelper();
    }


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
        $attributes = $this->attributeHelper->getAttributes($view);

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
