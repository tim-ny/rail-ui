<?php

namespace Aegis\Ui\Tests\Unit;

use Aegis\Ui\Components\Toggle;
use InvalidArgumentException;

it('resolves default classes for toggle', function () {
    $toggle = new Toggle();
    expect($toggle->classes())->toContain('ui-toggle', 'ui-toggle--md', 'ui-toggle--primary');
});

it('supports all sizes', function (string $size) {
    $toggle = new Toggle(size: $size);
    expect($toggle->classes())->toContain("ui-toggle--{$size}");
})->with(['xs', 'sm', 'md', 'lg', 'xl']);

it('supports all colors', function (string $color) {
    $toggle = new Toggle(color: $color);
    expect($toggle->classes())->toContain("ui-toggle--{$color}");
})->with(['primary', 'danger', 'success', 'warning', 'neutral']);

it('throws on invalid size', function () {
    expect(fn () => (new Toggle(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws on invalid color', function () {
    expect(fn () => (new Toggle(color: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('applies disabled class', function () {
    expect((new Toggle(disabled: true))->classes())->toContain('ui-toggle--disabled');
});

it('generates id from label', function () {
    $toggle = new Toggle(label: 'Enable Feature');
    expect($toggle->id)->toBe('ui-toggle-enable-feature');
});

it('generates random id when no label', function () {
    $toggle = new Toggle();
    expect($toggle->id)->toStartWith('ui-toggle-');
});

it('returns empty string when unstyled', function () {
    $toggle = new Toggle(unstyled: true);
    expect($toggle->classes())->toBe('');
});
