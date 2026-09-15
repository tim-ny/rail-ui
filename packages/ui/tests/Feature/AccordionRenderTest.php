<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders accordion container and item triggers', function () {
    $blade = <<<'BLADE'
<x-accordion variant="outline">
    <x-accordion-item title="Item 1">Content 1</x-accordion-item>
    <x-accordion-item title="Item 2">Content 2</x-accordion-item>
</x-accordion>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('x-data="{')
        ->toContain('ui-accordion')
        ->toContain('Item 1')
        ->toContain('Item 2');
});
