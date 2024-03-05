<?php

declare(strict_types=1);

namespace Mosaic\Helper;

/**
 * @internal
 */
final class Capture
{
    private Placeholder $placeholder;
    /** @var mixed */
    private mixed $view;

    /**
     * @param Placeholder $placeholder
     * @param mixed $view
     */
    public function __construct(Placeholder $placeholder, mixed $view)
    {
        $this->placeholder = $placeholder;
        $this->view = $view;
    }

    public function getPlaceholder(): Placeholder
    {
        return $this->placeholder;
    }

    /**
     * @return mixed
     */
    public function getView(): mixed
    {
        return $this->view;
    }
}