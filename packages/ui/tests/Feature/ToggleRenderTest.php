<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders toggle with label', function () {
    $rendered = Blade::render('<x-toggle label="Enable Feature" />');

    expect($rendered)
        ->toContain('ui-toggle')
        ->toContain('role="switch"')
        ->toContain('Enable Feature')
        ->toContain('ui-toggle__input');
});

it('renders toggle checked state', function () {
    $rendered = Blade::render('<x-toggle label="Active" checked />');

    expect($rendered)->toContain('on: true');
});

it('renders toggle disabled state', function () {
    $rendered = Blade::render('<x-toggle label="Locked" disabled />');

    expect($rendered)
        ->toContain('ui-toggle--disabled')
        ->toContain('disabled');
});

it('renders toggle with size and color', function () {
    $rendered = Blade::render('<x-toggle label="Big" size="lg" color="danger" />');

    expect($rendered)
        ->toContain('ui-toggle--lg')
        ->toContain('ui-toggle--danger');
});

it('renders toggle with error feedback', function () {
    $rendered = Blade::render('<x-toggle label="Required" error="This field is required." />');

    expect($rendered)
        ->toContain('ui-field--error')
        ->toContain('This field is required.');
});

it('renders toggle with wire:model', function () {
    $rendered = Blade::render('<x-toggle label="Synced" wireModel="enabled" />');

    expect($rendered)->toContain('wire:model');
});

it('renders toggle with required indicator', function () {
    $rendered = Blade::render('<x-toggle label="Required" required />');

    expect($rendered)
        ->toContain('ui-form-field__required')
        ->toContain('aria-required="true"');
});

it('renders toggle slot content as label', function () {
    $rendered = Blade::render('<x-toggle><span>Custom label</span></x-toggle>');

    expect($rendered)->toContain('Custom label');
});
