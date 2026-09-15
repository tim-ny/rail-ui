<?php

namespace Aegis\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders select with options', function () {
    $blade = <<<'BLADE'
<x-select name="fruit" :options="['apple', 'banana', 'cherry']" placeholder="Pick a fruit" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-select')
        ->toContain('Pick a fruit')
        ->toContain('role="listbox"');
});

it('renders select with label', function () {
    $blade = <<<'BLADE'
<x-select name="country" label="Country" :options="['US', 'UK']" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-select__label')
        ->toContain('Country');
});

it('renders select with array options', function () {
    $blade = <<<'BLADE'
<x-select name="lang" :options="[['value' => 'php', 'label' => 'PHP', 'icon' => 'brand-php'], ['value' => 'js', 'label' => 'JavaScript']]" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('PHP')
        ->toContain('JavaScript');
});

it('renders multiple select', function () {
    $blade = <<<'BLADE'
<x-select name="tags" multiple :options="['red', 'green', 'blue']" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)->toContain('aria-multiselectable="true"');
});

it('renders searchable select', function () {
    $blade = <<<'BLADE'
<x-select name="search" searchable :options="['foo', 'bar']" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)->toContain('ui-select__search');
});

it('renders clearable select', function () {
    $blade = <<<'BLADE'
<x-select name="clear" clearable :options="['a', 'b']" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)->toContain('ui-select__clear');
});

it('renders select with size and color', function () {
    $blade = <<<'BLADE'
<x-select name="sized" size="lg" color="danger" :options="['a']" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-select--lg')
        ->toContain('ui-select--danger');
});

it('renders select with error feedback', function () {
    $blade = <<<'BLADE'
<x-select name="required" error="Please select an option" :options="['a']" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-field--error')
        ->toContain('Please select an option');
});

it('renders select disabled state', function () {
    $blade = <<<'BLADE'
<x-select name="locked" disabled :options="['a']" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)->toContain('ui-select--disabled');
});

it('renders select with wire:model', function () {
    $blade = <<<'BLADE'
<x-select name="synced" wireModel="selected" :options="['a', 'b']" />
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)->toContain('wire:model');
});
