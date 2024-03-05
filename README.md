# Mosaic

**A Component Renderer for PHP**

Mosaic is a state-of-the-art server-side rendering engine for PHP. It offers an enhanced approach to efficiently and flexibly build and render UI components. Designed specifically for PHP developers, it blends seamlessly with the language’s native capabilities to deliver a rich, component-oriented development experience.

## Key Advantages

### Designed for PHP Developers
- **PHP-Native Experience**: Tailored for PHP without requiring adherence to an MVC model, Mosaic integrates smoothly into PHP projects, leveraging the language's strengths.

### Flexible Component Types
- **Versatile Component Strategies**: Beyond mere template rendering, Mosaic supports closures, fragments, and renderable objects, promoting modular and reusable design practices.

### Extensible and Customizable
- **Custom Strategies Out-of-the-Box**: The library enables custom rendering strategies for unmatched flexibility, perfect for projects with unique rendering needs.

### Lightweight and Performant
- **Minimal Overhead**: Designed to be lean, Mosaic delivers exceptional performance, suitable for projects ranging from small to large scale.

### Focused on Server-Side Rendering
- **Optimized for Server-Side Efficiency**: Exclusively targeting server-side rendering, it exploits server capabilities for rapid response times and efficient resource use.

## Installation

Install through Composer:

```bash
composer require robertkleinschuster/mosaic
```

## Usage

Here are examples for supported component types:

### Closure Components

Render PHP closures dynamically:

```php
$renderer->render(function ($data) {
    return "Hello, " . $data['name'];
}, ['name' => 'world']);
```

### Fragment Components

Compose UI fragments:

```php
$fragment = $renderer->fragment('<div>Hello, world</div>');
$renderer->render($fragment);
```

### Iterable Components

Loop over iterable data structures:

```php
$items = ['item1', 'item2', 'item3'];
$renderer->render($items);
```

### Renderable Components

Utilize objects implementing the Renderable interface:

```php
class MyComponent implements Renderable {
    public function render(\Mosaic\Renderer $renderer, $data) {
        return $renderer->fragment("<div>Hello, {$data['name']}</div>");
    }
}
$renderer->render(new MyComponent(), ['name' => 'world']);
```

### String Components

Handle simple strings:

```php
$renderer->render("Hello, world");
```

## Extending Mosaic

Its design allows for straightforward expansion, enabling developers to create custom rendering strategies, helper utilities, and more to meet specific application requirements.

## Running Tests

To execute the test suite, run the appropriate command in your CLI.

## Contributing

Contributions are highly appreciated, whether through issue reporting, feature suggestions, or direct code contributions.

## License

Distributed under the MIT License. See the LICENSE file for more information.

## Getting Help

For questions, issues, or assistance, please open an issue on GitHub.
