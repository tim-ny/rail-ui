<?php

namespace Aegis\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders radio group with radios', function () {
    $blade = <<<'BLADE'
<x-radio-group name="framework" value="laravel">
    <x-radio value="tailwind" label="Tailwind CSS" description="Utility-first CSS" />
    <x-radio value="alpine" label="Alpine JS" description="Lightweight JS" />
    <x-radio value="laravel" label="Laravel" description="PHP Framework" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('x-data="{ selected: ')
        ->toContain('ui-radio-group')
        ->toContain('role="radiogroup"')
        ->toContain('Tailwind CSS')
        ->toContain('Alpine JS')
        ->toContain('Laravel');
});

it('renders radio group with label', function () {
    $blade = <<<'BLADE'
<x-radio-group name="plan" label="Select plan">
    <x-radio value="free" label="Free" />
    <x-radio value="pro" label="Pro" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-radio-group__label')
        ->toContain('Select plan');
});

it('renders radio with pre-selected value', function () {
    $blade = <<<'BLADE'
<x-radio-group name="color" value="blue">
    <x-radio value="red" label="Red" />
    <x-radio value="blue" label="Blue" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)->toContain('selected:');
});

it('renders radio group with error feedback', function () {
    $blade = <<<'BLADE'
<x-radio-group name="choice" error="Please select an option">
    <x-radio value="a" label="Option A" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-field--error')
        ->toContain('Please select an option');
});

it('renders radio group with hint feedback', function () {
    $blade = <<<'BLADE'
<x-radio-group name="choice" hint="Pick one">
    <x-radio value="a" label="Option A" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-form-field__feedback--hint')
        ->toContain('Pick one');
});

it('renders radio group disabled state', function () {
    $blade = <<<'BLADE'
<x-radio-group name="locked" disabled>
    <x-radio value="a" label="Option A" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)->toContain('ui-radio-group--disabled');
});

it('renders radio group with size and color', function () {
    $blade = <<<'BLADE'
<x-radio-group name="choice" size="lg" color="danger">
    <x-radio value="a" label="Option A" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-radio-group--lg')
        ->toContain('ui-radio-group--danger');
});

it('renders radio with description', function () {
    $blade = <<<'BLADE'
<x-radio-group name="plan">
    <x-radio value="pro" label="Pro" description="Best for teams" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-radio__description')
        ->toContain('Best for teams');
});

it('renders radio with required indicator', function () {
    $blade = <<<'BLADE'
<x-radio-group name="choice" label="Pick one" required>
    <x-radio value="a" label="Option A" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-form-field__required')
        ->toContain('aria-required="true"');
});

it('renders radio with wire:model', function () {
    $blade = <<<'BLADE'
<x-radio-group name="choice" wireModel="selected">
    <x-radio value="a" label="Option A" />
</x-radio-group>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)->toContain('wire:model');
});
