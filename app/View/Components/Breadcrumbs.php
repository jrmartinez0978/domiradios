<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public array $items;
    public string $schema;

    /**
     * Create a new component instance.
     *
     * @param  array  $items  Each item should be an array with 'name' and 'url'.
     * @return void
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->schema = $this->generateSchema();
    }

    protected function generateSchema(): string
    {
        $schemaItems = [];
        foreach ($this->items as $index => $item) {
            $schemaItems[] = [
                "@type" => "ListItem",
                "position" => $index + 1,
                "name" => $item['name'],
                // Use item's URL if provided, otherwise, it might be the current page (no item for last breadcrumb)
                "item" => $item['url'] ?? ($index === count($this->items) - 1 ? url()->current() : null)
            ];
        }

        // Filter out items that couldn't resolve a URL (e.g., last item if not explicitly given one and not current page)
        $schemaItems = array_filter($schemaItems, fn($schemaItem) => $schemaItem['item'] !== null);

        $schema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => array_values($schemaItems) // Re-index array after filter
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.breadcrumbs');
    }
}
