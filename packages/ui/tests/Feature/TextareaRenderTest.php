<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;

it('renders textarea with label and feedback text', function () {
    $rendered = Blade::render('<x-textarea label="About" hint="Tell us about yourself." />');

    expect($rendered)
        ->toContain('<label for="ui-about"')
        ->toContain('About')
        ->toContain('Tell us about yourself.')
        ->toContain('id="ui-about-feedback"')
        ->toContain('<textarea');
});

it('renders error state with role alert', function () {
    $rendered = Blade::render('<x-textarea label="Bio" error="Bio is required" />');

    expect($rendered)
        ->toContain('aria-invalid="true"')
        ->toContain('role="alert"')
        ->toContain('Bio is required');
});

it('renders character count when maxlength is set', function () {
    $rendered = Blade::render('<x-textarea label="Bio" maxlength="200" />');

    expect($rendered)
        ->toContain('ui-form-field__charcount')
        ->toContain('x-data=')
        ->toContain('200')
        ->toContain('count +');
});

it('renders rows attribute', function () {
    $rendered = Blade::render('<x-textarea label="Notes" rows="8" />');

    expect($rendered)
        ->toContain('rows="8"');
});

it('renders readonly state', function () {
    $rendered = Blade::render('<x-textarea label="Notes" readonly value="Read only content" />');

    expect($rendered)
        ->toContain('readonly="readonly"')
        ->toContain('Read only content');
});

it('renders disabled state', function () {
    $rendered = Blade::render('<x-textarea label="Notes" disabled />');

    expect($rendered)
        ->toContain('disabled="disabled"');
});

it('defaults to 3 rows', function () {
    $rendered = Blade::render('<x-textarea label="Notes" />');

    expect($rendered)
        ->toContain('rows="3"');
});

it('renders custom rows value', function () {
    $rendered = Blade::render('<x-textarea label="Notes" rows="8" />');

    expect($rendered)
        ->toContain('rows="8"');
});

it('renders auto-resize wrapper when autoResize is true', function () {
    $rendered = Blade::render('<x-textarea label="Bio" autoResize />');

    expect($rendered)
        ->toContain('ui-textarea--auto-resize')
        ->toContain('resize()')
        ->toContain('scrollHeight');
});

it('does not render auto-resize wrapper by default', function () {
    $rendered = Blade::render('<x-textarea label="Bio" />');

    expect($rendered)
        ->not->toContain('ui-textarea--auto-resize')
        ->not->toContain('scrollHeight');
});
