<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders button element with default classes', function () {
    $rendered = Blade::render('<x-button>Click Me</x-button>');

    expect($rendered)
        ->toContain('<button')
        ->toContain('class="ui-btn ui-btn--solid ui-btn--md ui-btn--primary"')
        ->toContain('Click Me');
});

it('renders link tag when href is passed', function () {
    $rendered = Blade::render('<x-button href="https://example.com" external>Link</x-button>');

    expect($rendered)
        ->toContain('<a')
        ->toContain('href="https://example.com"')
        ->toContain('target="_blank"')
        ->toContain('rel="noopener noreferrer"');
});

it('renders loading spinner and disabled state', function () {
    $rendered = Blade::render('<x-button loading>Saving</x-button>');

    expect($rendered)
        ->toContain('disabled="disabled"')
        ->toContain('aria-busy="true"')
        ->toContain('ui-spinner');
});
