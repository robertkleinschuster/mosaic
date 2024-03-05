<?php

declare(strict_types=1);

namespace Mosaic\Helper;

use Mosaic\Fragment;
use Mosaic\Renderable;
use Mosaic\Renderer;


/**
 * @internal
 */
final class Placeholder implements Renderable
{
    private string $code;

    /**
     * @var array<int, mixed>
     */
    private array $replacements = [];

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param mixed $view
     * @return $this
     */
    public function addReplacement(mixed $view): self
    {
        $this->replacements[] = $view;
        return $this;
    }

    /**
     * @return Capture[]
     */
    public function getReplacements(): array
    {
        return $this->replacements;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return iterable<mixed, mixed>
     */
    public function render(Renderer $renderer, mixed $data = null): iterable
    {
        yield new Fragment(<<<HTML
<script type="text/html" id="$this->code"></script>
HTML
        );
    }
}