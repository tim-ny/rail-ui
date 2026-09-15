<?php

namespace Aegis\Ui\Tests\Unit;

use Aegis\Ui\Components\Select;
use InvalidArgumentException;

it('resolves default classes for select', function () {
    $select = new Select();
    expect($select->classes())->toContain('ui-select', 'ui-select--md', 'ui-select--primary');
});

it('supports all sizes', function (string $size) {
    $select = new Select(size: $size);
    expect($select->classes())->toContain("ui-select--{$size}");
})->with(['xs', 'sm', 'md', 'lg', 'xl']);

it('supports all colors', function (string $color) {
    $select = new Select(color: $color);
    expect($select->classes())->toContain("ui-select--{$color}");
})->with(['primary', 'danger', 'success', 'warning', 'neutral']);

it('throws on invalid size', function () {
    expect(fn () => (new Select(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws on invalid color', function () {
    expect(fn () => (new Select(color: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('applies disabled class', function () {
    expect((new Select(disabled: true))->classes())->toContain('ui-select--disabled');
});

it('applies readonly class', function () {
    expect((new Select(readonly: true))->classes())->toContain('ui-select--readonly');
});

it('normalizes string options', function () {
    $select = new Select(options: ['foo', 'bar', 'baz']);
    expect($select->options)->toHaveCount(3)
        ->and($select->options[0]['value'])->toBe('foo')
        ->and($select->options[0]['label'])->toBe('foo');
});

it('normalizes array options', function () {
    $select = new Select(options: [
        ['value' => 'us', 'label' => 'United States'],
        ['value' => 'uk', 'label' => 'United Kingdom', 'disabled' => true],
    ]);
    expect($select->options)->toHaveCount(2)
        ->and($select->options[0]['value'])->toBe('us')
        ->and($select->options[1]['disabled'])->toBeTrue();
});

it('normalizes options with icon and image', function () {
    $select = new Select(options: [
        ['value' => 'a', 'label' => 'Item A', 'icon' => 'star', 'image' => '/img/a.png'],
    ]);
    expect($select->options[0]['icon'])->toBe('star')
        ->and($select->options[0]['image'])->toBe('/img/a.png');
});

it('generates id from label', function () {
    $select = new Select(label: 'Country');
    expect($select->id)->toBe('ui-select-country');
});

it('returns empty string when unstyled', function () {
    $select = new Select(unstyled: true);
    expect($select->classes())->toBe('');
});
