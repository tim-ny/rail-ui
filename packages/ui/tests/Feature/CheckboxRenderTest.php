<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders checkbox with label and default classes', function () {
    $rendered = Blade::render('<x-checkbox label="Accept Terms" />');

    expect($rendered)
        ->toContain('type="checkbox"')
        ->toContain('ui-checkbox ui-checkbox--md ui-checkbox--primary')
        ->toContain('ui-checkbox__box')
        ->toContain('ui-checkbox__check')
        ->toContain('Accept Terms');
});

it('renders checked state', function () {
    $rendered = Blade::render('<x-checkbox label="Subscribe" checked />');

    expect($rendered)
        ->toContain('checked="checked"');
});

it('renders indeterminate state via Alpine', function () {
    $rendered = Blade::render('<x-checkbox label="Select all" indeterminate />');

    expect($rendered)
        ->toContain('x-init="$el.indeterminate = uiIndeterminate"')
        ->toContain('ui-checkbox__dash');
});

it('renders disabled and readonly states', function () {
    expect(Blade::render('<x-checkbox label="Locked" disabled />'))
        ->toContain('disabled="disabled"')
        ->toContain('ui-checkbox--disabled');

    expect(Blade::render('<x-checkbox label="Locked" readonly />'))
        ->toContain('disabled="disabled"')
        ->toContain('ui-checkbox--readonly');
});

it('renders error feedback with aria attributes', function () {
    $rendered = Blade::render('<x-checkbox label="Agree" error="You must agree." />');

    expect($rendered)
        ->toContain('ui-field--error')
        ->toContain('aria-invalid="true"')
        ->toContain('role="alert"')
        ->toContain('You must agree.');
});

it('renders hint feedback', function () {
    $rendered = Blade::render('<x-checkbox label="Newsletter" hint="We email monthly." />');

    expect($rendered)
        ->toContain('ui-form-field__feedback--hint')
        ->toContain('We email monthly.');
});

it('renders wire:model attribute when wireModel is set', function () {
    $rendered = Blade::render('<x-checkbox label="Opt in" wireModel="agree" />');

    expect($rendered)
        ->toContain('wire:model');
});

it('renders radius level class', function () {
    $rendered = Blade::render('<x-checkbox label="Rounded" radius="full" />');

    expect($rendered)
        ->toContain('ui-checkbox--radius-full');
});

it('renders block mode with slot content in a label card', function () {
    $rendered = Blade::render(
        '<x-checkbox name="newsletter" value="1">Enable weekly newsletter</x-checkbox>'
    );

    expect($rendered)
        ->toContain('ui-checkbox--block')
        ->toContain('ui-checkbox__wrapper--block')
        ->toContain('ui-checkbox__label--block')
        ->toContain('Enable weekly newsletter');
});

it('renders block mode via block attribute', function () {
    $rendered = Blade::render(
        '<x-checkbox block label="Card option" />'
    );

    expect($rendered)
        ->toContain('ui-checkbox--block')
        ->toContain('ui-checkbox__wrapper--block')
        ->toContain('ui-checkbox__label--block');
});
