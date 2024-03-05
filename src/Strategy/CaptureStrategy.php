<?php

declare(strict_types=1);

namespace Mosaic\Strategy;

use Mosaic\Exception\RenderException;
use Mosaic\Fragment;
use Mosaic\FragmentCollection;
use Mosaic\Helper\Capture;
use Mosaic\Helper\Placeholder;
use Mosaic\Renderer;
use Mosaic\Strategy\Base\PipelineStrategy;
use Throwable;

final class CaptureStrategy extends PipelineStrategy
{
    /** @var Placeholder[] */
    private array $placeholders = [];

    /**
     * @var Capture[]
     */
    private array $captures = [];

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return FragmentCollection
     * @throws RenderException|Throwable
     */
    public function execute(mixed $view, Renderer $renderer, mixed $data): FragmentCollection
    {
        if ($renderer->getLevel() === 1) {
            $this->placeholders = [];
            $this->captures = [];
        }

        if ($view instanceof Placeholder) {
            if (isset($this->placeholders[$view->getCode()])) {
                throw new RenderException(sprintf('Placeholder "%s" already in use.', $view->getCode()));
            }
            $this->placeholders[$view->getCode()] = $view;
        }

        if ($view instanceof Capture) {
            $this->captures[] = $view;
            return new FragmentCollection();
        }

        $result = $this->next($view, $renderer, $data);

        if ($renderer->getLevel() === 1) {
            return $this->replacePlaceholders($result, $renderer);
        }
        return $result;
    }

    /**
     * @param FragmentCollection $fragments
     * @param Renderer $renderer
     * @return FragmentCollection
     * @throws RenderException|Throwable
     */
    private function replacePlaceholders(FragmentCollection $fragments, Renderer $renderer): FragmentCollection
    {
        foreach ($this->captures as $capture) {
            if (!isset($this->placeholders[$capture->getPlaceholder()->getCode()])) {
                throw new RenderException(
                    sprintf('Placeholder "%s" not found.', $capture->getPlaceholder()->getCode())
                );
            }
            $this->placeholders[$capture->getPlaceholder()->getCode()]->addReplacement($capture->getView());
        }

        $placeholders = [];
        $replacements = [];
        foreach ($this->placeholders as $placeholder) {
            $placeholders[] = $renderer->render($placeholder->render($renderer));
            $replacements[] = $renderer->render($placeholder->getReplacements());
        }

        $result = new FragmentCollection();
        foreach ($fragments as $fragment) {
            $result->push(new Fragment(str_replace($placeholders, $replacements, (string)$fragment)));
        }
        return $result;
    }
}