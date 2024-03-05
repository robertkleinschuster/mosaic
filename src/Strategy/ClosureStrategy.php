<?php

declare(strict_types=1);

namespace Mosaic\Strategy;

use Closure;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use Mosaic\Exception\RenderException;
use Mosaic\FragmentCollection;
use Mosaic\Helper\Arguments;
use Mosaic\Renderer;
use Mosaic\Strategy\Base\PipelineStrategy;
use SplObjectStorage;

final class ClosureStrategy extends PipelineStrategy
{
    /** @var SplObjectStorage<Closure, ReflectionFunction> */
    private SplObjectStorage $storage;

    protected function init(): void
    {
        parent::init();
        $this->storage = new SplObjectStorage();
    }

    /**
     * @param Closure $view
     * @return ReflectionFunction
     * @throws ReflectionException
     */
    private function getReflection(Closure $view): ReflectionFunction
    {
        if (!$this->storage->contains($view)) {
            $this->storage[$view] = new ReflectionFunction($view);
        }

        return $this->storage[$view];
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return FragmentCollection
     * @throws RenderException
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        if ($view instanceof Closure) {
            $args = [];
            try {
                foreach ($this->getReflection($view)->getParameters() as $parameter) {
                    $position = $parameter->getPosition();
                    $args[$position] = $data;
                    $type = $parameter->getType();
                    if ($type instanceof ReflectionNamedType && $type->getName() === Renderer::class) {
                        $args[$position] = $renderer;
                    } elseif ($data instanceof Arguments) {
                        $args[$position] = $data[$parameter->getName()] ?? null;
                    }
                }
                ksort($args, SORT_NUMERIC);
            } catch (ReflectionException $exception) {
                throw RenderException::forThrowableInView($exception, $view);
            }
            return (new StringStrategy($renderer))->execute($view(...$args), $renderer, $data);
        }
        return $this->next($view, $renderer, $data);
    }
}