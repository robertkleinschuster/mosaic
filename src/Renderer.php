<?php

declare(strict_types=1);

namespace Mosaic;

use Closure;
use Mosaic\Exception\RenderException;
use Mosaic\Helper\Arguments;
use Mosaic\Helper\Capture;
use Mosaic\Helper\Conditional;
use Mosaic\Helper\IncludeFile;
use Mosaic\Helper\Loop;
use Mosaic\Helper\Placeholder;
use Throwable;

class Renderer implements Strategy
{
    private Strategy $strategy;
    private int $maxLevel = 0;
    private int $level = 0;
    private bool $wrapErrors = true;
    private bool $muteErrors = false;

    public function __construct(Strategy $strategy = null, int $maxLevel = 256)
    {
        $this->strategy = $strategy ?? (new StrategyPipelineFactory())->create();
        $this->setMaxLevel($maxLevel);
    }

    /**
     * @param mixed $view
     * @param mixed|null $data
     * @param mixed ...$args
     * @return FragmentCollection
     * @throws RenderException
     * @throws Throwable
     */
    public function render(mixed $view, mixed $data = null, mixed ...$args): FragmentCollection
    {
        try {
            return $this->execute($view, $this, $data ?? $this->args($args));
        } catch (Throwable $throwable) {
            if ($this->isMuteErrors()) {
                return new FragmentCollection();
            } else if ($this->isWrapErrors()) {
                throw RenderException::forThrowableInView($throwable, $view);
            } else {
                throw $throwable;
            }
        }
    }

    /**
     * @param mixed $view
     * @param iterable<int, mixed> $items
     * @return FragmentCollection
     * @throws RenderException|Throwable
     */
    public function loop(mixed $view, iterable $items): FragmentCollection
    {
        return $this->render(new Loop($view, $items));
    }

    /**
     * @param mixed $view
     * @param Closure $predicate
     * @param mixed|null $data
     * @return FragmentCollection
     * @throws RenderException|Throwable
     */
    public function conditional(mixed $view, Closure $predicate, mixed $data = null, mixed ...$args): FragmentCollection
    {
        return $this->render(new Conditional($view, $predicate), $data, ...$args);
    }

    /**
     * @param string $placeholder
     * @param mixed $view
     * @return FragmentCollection
     * @throws RenderException|Throwable
     */
    public function capture(string $placeholder, mixed $view): FragmentCollection
    {
        return $this->render(new Capture(new Placeholder($placeholder), $view));
    }

    /**
     * @throws RenderException|Throwable
     */
    public function placeholder(string $code): FragmentCollection
    {
        return $this->render(new Placeholder($code));
    }

    /**
     * @param array<string, mixed> $args
     * @return Arguments
     */
    public function arguments(array $args): Arguments
    {
        return new Arguments($args);
    }

    /**
     * @param string $content
     * @return FragmentCollection
     * @throws RenderException|Throwable
     */
    public function fragment(string $content): FragmentCollection
    {
        return $this->render(new Fragment($content));
    }

    /**
     * @param string $file
     * @param mixed|null $data
     * @return FragmentCollection
     * @throws RenderException
     * @throws Throwable
     */
    public function include(string $file, mixed $data = null, mixed ...$args): FragmentCollection
    {
        return $this->render(new IncludeFile($file), $data, ...$args);
    }

    /**
     * @param array<string, mixed> $args
     * @return Arguments
     */
    public function args(array $args): Arguments
    {
        return $this->arguments($args);
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed $data
     * @return FragmentCollection
     * @throws RenderException
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        $clone = clone $this;
        $clone->assertLevel($view);
        return $this->strategy->execute($view, $clone, $data);
    }

    public function getMaxLevel(): int
    {
        return $this->maxLevel;
    }

    public function setMaxLevel(int $maxLevel): void
    {
        $this->maxLevel = $maxLevel;
    }

    public function getStrategy(): Strategy
    {
        return $this->strategy;
    }

    public function setStrategy(Strategy $strategy): Renderer
    {
        $this->strategy = $strategy;
        return $this;
    }

    public function isWrapErrors(): bool
    {
        return $this->wrapErrors;
    }

    public function setWrapErrors(bool $wrapErrors): Renderer
    {
        $this->wrapErrors = $wrapErrors;
        return $this;
    }

    public function isMuteErrors(): bool
    {
        return $this->muteErrors;
    }

    public function setMuteErrors(bool $muteErrors): Renderer
    {
        $this->muteErrors = $muteErrors;
        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param mixed $view
     * @return void
     * @throws RenderException
     */
    private function assertLevel(mixed $view): void
    {
        $this->level++;
        if ($this->getLevel() > $this->getMaxLevel()) {
            throw RenderException::forMaxNestingLevel($view, $this->getMaxLevel());
        }
    }
}