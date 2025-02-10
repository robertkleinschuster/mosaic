<?php

namespace Mosaic\Templates;

use Attribute;
use Mosaic\Fragment;
use Mosaic\RenderableAttribute;
use Mosaic\Renderer;

#[Attribute]
class Document implements RenderableAttribute
{
    /**
     * @param string $lang
     * @param string $title
     * @param string $description
     * @param string[] $styles
     * @param string[] $scripts
     */
    public function __construct(
        private string $lang,
        private string $title,
        private string $description,
        private array  $styles = [],
        private array  $scripts = [],
    )
    {
    }


    public function render(Renderer $renderer, mixed $children, mixed $data)
    {
        yield new Fragment(<<<HTML
<!doctype html>
<html lang="$this->lang">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>$this->title</title>
    <meta name="description" content="$this->description">
    {$renderer->foreach($this->styles, fn(string $style) => new Fragment('<link rel="stylesheet" href="{style}">', style: $style))}
    {$renderer->foreach($this->scripts, fn(string $script) => new Fragment('<script src="{script}"></script>', script: $script))}
</head>
<body>
    {$renderer->render($children, $data)}
</body>
</html>
HTML
        );
    }
}