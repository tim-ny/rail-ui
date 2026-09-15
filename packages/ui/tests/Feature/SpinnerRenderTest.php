<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders the default tabler loader icon with status semantics', function () {
    $rendered = Blade::render('<x-spinner />');

    expect($rendered)
        ->toContain('class="ui-spinner ui-spinner--md"')
        ->toContain('role="status"')
        ->toContain('aria-label="Loading"')
        ->toContain('<svg');
});

it('renders a custom icon passed inline', function () {
    $rendered = Blade::render('<x-spinner icon="refresh" label="Refreshing" />');

    expect($rendered)
        ->toContain('aria-label="Refreshing"')
        ->toContain('class="ui-spinner ui-spinner--md"');
});

it('renders the size class from the size prop', function () {
    $rendered = Blade::render('<x-spinner size="sm" />');

    expect($rendered)->toContain('ui-spinner ui-spinner--sm');
});

it('uses the configured loading icon by default', function () {
    config()->set('rail-ui.loading.icon', 'loader-2');

    $rendered = Blade::render('<x-spinner />');

    expect($rendered)->toContain('class="ui-spinner ui-spinner--md"');
});

it('renders the built-in svg spinner when icons are disabled', function () {
    config()->set('rail-ui.features.icons', false);

    $rendered = Blade::render('<x-spinner label="Waiting" />');

    expect($rendered)
        ->toContain('class="ui-spinner ui-spinner--md"')
        ->toContain('role="status"')
        ->toContain('aria-label="Waiting"')
        ->toContain('ui-spinner__track')
        ->toContain('ui-spinner__head');
});

it('renders a spinner inside a loading button', function () {
    $rendered = Blade::render('<x-button loading>Saving</x-button>');

    expect($rendered)
        ->toContain('disabled="disabled"')
        ->toContain('aria-busy="true"')
        ->toContain('class="ui-spinner ui-spinner--sm"');
});
