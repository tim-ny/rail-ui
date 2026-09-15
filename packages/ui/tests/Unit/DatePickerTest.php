<?php

namespace Rail\Ui\Tests\Unit;

use Rail\Ui\Components\DatePicker;
use InvalidArgumentException;

it('resolves default classes for datepicker', function () {
    $picker = new DatePicker();
    expect($picker->classes())
        ->toContain('ui-datepicker', 'ui-datepicker--md', 'ui-datepicker--primary', 'ui-datepicker--single');
});

it('supports all modes', function (string $mode) {
    $picker = new DatePicker(mode: $mode);
    expect($picker->classes())->toContain("ui-datepicker--{$mode}");
})->with(['single', 'range', 'multiple', 'month', 'month-range', 'year', 'year-range']);

it('throws on invalid mode', function () {
    expect(fn () => (new DatePicker(mode: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('supports all display formats', function (string $format) {
    $picker = new DatePicker(format: $format);
    expect($picker->format)->toBe($format);
})->with(['M d, Y', 'MM-DD-YYYY', 'DD-MM-YYYY', 'YYYY-MM-DD', 'D d M, Y', 'MMMM d, Y']);

it('throws on invalid format', function () {
    expect(fn () => (new DatePicker(format: 'not-a-format'))->classes())->toThrow(InvalidArgumentException::class);
});

it('supports all sizes and colors', function (string $size, string $color) {
    $picker = new DatePicker(size: $size, color: $color);
    expect($picker->classes())
        ->toContain("ui-datepicker--{$size}", "ui-datepicker--{$color}");
})->with(['xs', 'sm', 'md', 'lg', 'xl'], ['primary', 'danger', 'success', 'warning', 'neutral']);

it('applies week numbers, disabled and readonly classes', function () {
    expect((new DatePicker(weekNumbers: true))->classes())->toContain('ui-datepicker--week-numbers');
    expect((new DatePicker(disabled: true))->classes())->toContain('ui-datepicker--disabled');
    expect((new DatePicker(readonly: true))->classes())->toContain('ui-datepicker--readonly');
});

it('maps mode to granularity and selection type', function (string $mode, string $granularity, string $type) {
    $picker = new DatePicker(mode: $mode);
    expect($picker->granularity())->toBe($granularity);
    expect($picker->selectType())->toBe($type);
})->with([
    ['single', 'day', 'single'],
    ['multiple', 'day', 'multiple'],
    ['range', 'day', 'range'],
    ['month', 'month', 'single'],
    ['month-range', 'month', 'range'],
    ['year', 'year', 'single'],
    ['year-range', 'year', 'range'],
]);

it('normalizes single value', function () {
    $picker = new DatePicker(value: '2026-08-14');
    expect($picker->initialSelected())->toBe('2026-08-14');
});

it('normalizes range value from string and array', function () {
    expect((new DatePicker(mode: 'range', value: '2026-08-01 - 2026-08-14'))->initialSelected())
        ->toBe(['start' => '2026-08-01', 'end' => '2026-08-14']);

    expect((new DatePicker(mode: 'range', value: ['2026-08-01', '2026-08-14']))->initialSelected())
        ->toBe(['start' => '2026-08-01', 'end' => '2026-08-14']);
});

it('normalizes multiple value from array and string', function () {
    expect((new DatePicker(mode: 'multiple', value: ['2026-08-01', '2026-08-14']))->initialSelected())
        ->toBe(['2026-08-01', '2026-08-14']);

    expect((new DatePicker(mode: 'multiple', value: '2026-08-01,2026-08-14'))->initialSelected())
        ->toBe(['2026-08-01', '2026-08-14']);
});

it('builds wire value for each selection type', function (string $mode, mixed $value, string $expected) {
    $picker = new DatePicker(mode: $mode, value: $value);
    expect($picker->wireValue())->toBe($expected);
})->with([
    ['single', '2026-08-14', '2026-08-14'],
    ['range', '2026-08-01 - 2026-08-14', '2026-08-01 - 2026-08-14'],
    ['multiple', ['2026-08-01', '2026-08-14'], '2026-08-01,2026-08-14'],
]);

it('uses provided initial view year and month', function () {
    $picker = new DatePicker(value: '2026-08-14');
    expect($picker->initialViewYear())->toBe(2026);
    expect($picker->initialViewMonth())->toBe(7);
});

it('falls back to today for initial view', function () {
    $picker = new DatePicker();
    expect($picker->initialViewYear())->toBe((int) now()->format('Y'));
    expect($picker->initialViewMonth())->toBe((int) now()->format('n') - 1);
});

it('builds default presets when enabled', function () {
    $picker = new DatePicker(presets: true);
    $presets = $picker->presetsConfig();

    expect($presets)->not->toBeEmpty();
    expect($presets[0])->toMatchArray(['label' => 'Today']);
});

it('passes through custom presets', function () {
    $custom = [['label' => 'Custom', 'start' => '2026-01-01', 'end' => '2026-01-31']];
    $picker = new DatePicker(presets: $custom);
    expect($picker->presetsConfig())->toBe($custom);
});

it('returns empty presets when disabled', function () {
    expect((new DatePicker())->presetsConfig())->toBe([]);
});
