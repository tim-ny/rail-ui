<?php

namespace Rail\Ui\Tests\Unit;

use Rail\Ui\Components\Checkbox;
use InvalidArgumentException;

it('resolves default classes for checkbox', function () {
    $checkbox = new Checkbox();
    expect($checkbox->classes())->toContain('ui-checkbox', 'ui-checkbox--md', 'ui-checkbox--primary', 'ui-checkbox--radius-sm');
});

it('supports all sizes', function (string $size) {
    $checkbox = new Checkbox(size: $size);
    expect($checkbox->classes())->toContain("ui-checkbox--{$size}");
})->with(['xs', 'sm', 'md', 'lg', 'xl']);

it('supports all colors', function (string $color) {
    $checkbox = new Checkbox(color: $color);
    expect($checkbox->classes())->toContain("ui-checkbox--{$color}");
})->with(['primary', 'danger', 'success', 'warning', 'neutral']);

it('supports all radius levels', function (string $radius) {
    $checkbox = new Checkbox(radius: $radius);
    expect($checkbox->classes())->toContain("ui-checkbox--radius-{$radius}");
})->with(['none', 'xs', 'sm', 'md', 'lg', 'full']);

it('throws on invalid size', function () {
    expect(fn () => (new Checkbox(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws on invalid color', function () {
    expect(fn () => (new Checkbox(color: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws on invalid radius', function () {
    expect(fn () => (new Checkbox(radius: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('applies disabled, readonly and block classes', function () {
    expect((new Checkbox(disabled: true))->classes())->toContain('ui-checkbox--disabled');
    expect((new Checkbox(readonly: true))->classes())->toContain('ui-checkbox--readonly');
    expect((new Checkbox(block: true))->classes())->toContain('ui-checkbox--block');
});

it('auto generates id and name from label', function () {
    $checkbox = new Checkbox(label: 'Accept Terms');
    expect($checkbox->id)->toBe('ui-accept-terms');
    expect($checkbox->name)->toBe('ui-accept-terms');
});

it('throws on invalid wire:model modifier', function () {
    expect(fn () => (new Checkbox(wireModel: 'agree', wireModelModifier: 'invalid'))->wireModelAttribute())
        ->toThrow(InvalidArgumentException::class);
});
