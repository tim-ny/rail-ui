<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders datepicker with label and default classes', function () {
    $rendered = Blade::render('<x-datepicker label="Start date" />');

    expect($rendered)
        ->toContain('ui-datepicker ui-datepicker--md ui-datepicker--primary ui-datepicker--single')
        ->toContain('ui-datepicker__trigger')
        ->toContain('ui-datepicker__popover')
        ->toContain('type="text"')
        ->toContain('Start date');
});

it('renders range mode', function () {
    $rendered = Blade::render('<x-datepicker mode="range" label="Trip dates" value="2026-08-01 - 2026-08-14" />');

    expect($rendered)
        ->toContain('ui-datepicker--range')
        ->toContain('granularity')
        ->toContain('selectType');
});

it('renders week numbers flag', function () {
    $rendered = Blade::render('<x-datepicker weekNumbers label="Leave" />');

    expect($rendered)
        ->toContain('ui-datepicker--week-numbers');
});

it('renders presets sidebar when presets are enabled', function () {
    $rendered = Blade::render('<x-datepicker mode="range" presets label="Report period" />');

    expect($rendered)
        ->toContain('ui-datepicker__presets')
        ->toContain('x-for="(p, i) in presets"');
});

it('renders clear button when clearable', function () {
    $rendered = Blade::render('<x-datepicker clearable label="Due date" />');

    expect($rendered)
        ->toContain('ui-datepicker__clear');
});

it('renders disabled and readonly states', function () {
    expect(Blade::render('<x-datepicker disabled label="Locked" />'))
        ->toContain('ui-datepicker--disabled')
        ->toContain('disabled');

    expect(Blade::render('<x-datepicker readonly label="Readonly" />'))
        ->toContain('ui-datepicker--readonly');
});

it('renders error feedback with aria attributes', function () {
    $rendered = Blade::render('<x-datepicker label="Booking" error="Pick a date." />');

    expect($rendered)
        ->toContain('ui-field--error')
        ->toContain('aria-invalid="true"')
        ->toContain('role="alert"')
        ->toContain('Pick a date.');
});

it('renders wire:model on a hidden input', function () {
    $rendered = Blade::render('<x-datepicker label="Start" wireModel="start_date" />');

    expect($rendered)
        ->toContain('wire:model')
        ->toContain('type="hidden"')
        ->toContain('x-effect=');
});

it('renders month and year modes', function () {
    expect(Blade::render('<x-datepicker mode="month" label="Billing month" />'))
        ->toContain('ui-datepicker--month')
        ->toContain('ui-datepicker__grid--month');

    expect(Blade::render('<x-datepicker mode="year" label="Year" />'))
        ->toContain('ui-datepicker--year')
        ->toContain('ui-datepicker__grid--year');
});
