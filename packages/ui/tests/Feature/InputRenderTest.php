<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders input with label and feedback text', function () {
    $rendered = Blade::render('<x-input label="Email" hint="We will never share your email." />');

    expect($rendered)
        ->toContain('<label for="ui-email"')
        ->toContain('Email')
        ->toContain('We will never share your email.')
        ->toContain('id="ui-email-feedback"');
});

it('renders password toggle when type is password', function () {
    $rendered = Blade::render('<x-input type="password" label="Password" />');

    expect($rendered)
        ->toContain('x-data="{ showPassword: false }"')
        ->toContain('ui-input__password-toggle');
});

it('renders error state with role alert', function () {
    $rendered = Blade::render('<x-input label="Email" error="Email is required" />');

    expect($rendered)
        ->toContain('aria-invalid="true"')
        ->toContain('role="alert"')
        ->toContain('Email is required');
});
