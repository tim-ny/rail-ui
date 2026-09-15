<?php

namespace Aegis\Ui\Tests\Unit;

use Aegis\Ui\Components\RadioGroup;
use Aegis\Ui\Components\Radio;
use InvalidArgumentException;

it('resolves default classes for radio group', function () {
    $group = new RadioGroup();
    expect($group->classes())->toContain('ui-radio-group', 'ui-radio-group--md', 'ui-radio-group--primary');
});

it('supports all sizes', function (string $size) {
    $group = new RadioGroup(size: $size);
    expect($group->classes())->toContain("ui-radio-group--{$size}");
})->with(['xs', 'sm', 'md', 'lg', 'xl']);

it('supports all colors', function (string $color) {
    $group = new RadioGroup(color: $color);
    expect($group->classes())->toContain("ui-radio-group--{$color}");
})->with(['primary', 'danger', 'success', 'warning', 'neutral']);

it('throws on invalid size', function () {
    expect(fn () => (new RadioGroup(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws on invalid color', function () {
    expect(fn () => (new RadioGroup(color: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('applies disabled class', function () {
    expect((new RadioGroup(disabled: true))->classes())->toContain('ui-radio-group--disabled');
});

it('generates unique id', function () {
    $group = new RadioGroup();
    expect($group->id)->toStartWith('ui-radio-group-');
});

it('radio resolves default classes', function () {
    $radio = new Radio();
    expect($radio->classes())->toContain('ui-radio');
});

it('radio supports disabled class', function () {
    $radio = new Radio(disabled: true);
    expect($radio->classes())->toContain('ui-radio--disabled');
});

it('radio returns empty string when unstyled', function () {
    $radio = new Radio(unstyled: true);
    expect($radio->classes())->toBe('');
});

it('radio group returns empty string when unstyled', function () {
    $group = new RadioGroup(unstyled: true);
    expect($group->classes())->toBe('');
});
