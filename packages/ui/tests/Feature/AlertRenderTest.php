<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders alert headline and body with default role', function () {
    $rendered = Blade::render('<x-alert title="Notification" color="primary">Alert body text</x-alert>');

    expect($rendered)
        ->toContain('role="status"')
        ->toContain('Notification')
        ->toContain('Alert body text');
});

it('renders role alert for danger color', function () {
    $rendered = Blade::render('<x-alert title="Error" color="danger">Something went wrong</x-alert>');

    expect($rendered)
        ->toContain('role="alert"')
        ->toContain('ui-alert--danger');
});

it('renders dismiss button when dismissible is true', function () {
    $rendered = Blade::render('<x-alert title="Dismissible" dismissible>Can be closed</x-alert>');

    expect($rendered)
        ->toContain('x-data="{ show: true }"')
        ->toContain('ui-alert__dismiss');
});
