<?php

namespace Aegis\Ui\Tests\Unit;

use Aegis\Ui\Components\Dropdown;
use Aegis\Ui\Components\DropdownItem;
use Aegis\Ui\Components\DropdownHeader;
use Aegis\Ui\Components\DropdownDivider;
use Aegis\Ui\Components\DropdownCheckbox;
use Aegis\Ui\Components\DropdownSubmenu;
use InvalidArgumentException;

it('resolves left solid primary classes by default for dropdown', function () {
    $dropdown = new Dropdown();
    expect($dropdown->classes())->toContain('ui-dropdown', 'ui-dropdown--left');
});

it('supports left right center alignment', function (string $align) {
    $dropdown = new Dropdown(align: $align);
    expect($dropdown->classes())->toContain("ui-dropdown--{$align}");
})->with(['left', 'right', 'center']);

it('supports solid soft outline ghost variants', function (string $variant) {
    $dropdown = new Dropdown(variant: $variant);
    expect($dropdown->classes())->toContain("ui-dropdown--{$variant}");
})->with(['solid', 'soft', 'outline', 'ghost']);

it('supports sm md lg sizes', function (string $size) {
    $dropdown = new Dropdown(size: $size);
    expect($dropdown->classes())->toContain("ui-dropdown--{$size}");
})->with(['sm', 'md', 'lg']);

it('supports primary neutral danger success warning colors', function (string $color) {
    $dropdown = new Dropdown(color: $color);
    expect($dropdown->classes())->toContain("ui-dropdown--{$color}");
})->with(['primary', 'neutral', 'danger', 'success', 'warning']);

it('throws exception on invalid variant', function () {
    expect(fn () => (new Dropdown(variant: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws exception on invalid size', function () {
    expect(fn () => (new Dropdown(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws exception on invalid color', function () {
    expect(fn () => (new Dropdown(color: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('returns empty string when unstyled is true', function () {
    $dropdown = new Dropdown(unstyled: true);
    expect($dropdown->classes())->toBe('');
});

it('dropdown item resolves default classes', function () {
    $item = new DropdownItem();
    expect($item->classes())->toContain('ui-dropdown__item');
});

it('dropdown item supports danger class', function () {
    $item = new DropdownItem(danger: true);
    expect($item->classes())->toContain('ui-dropdown__item--danger');
});

it('dropdown item supports disabled class', function () {
    $item = new DropdownItem(disabled: true);
    expect($item->classes())->toContain('ui-dropdown__item--disabled');
});

it('dropdown header resolves default classes', function () {
    $header = new DropdownHeader();
    expect($header->classes())->toBe('ui-dropdown__header');
});

it('dropdown divider resolves default classes', function () {
    $divider = new DropdownDivider();
    expect($divider->classes())->toBe('ui-dropdown__divider');
});

it('dropdown checkbox resolves default classes', function () {
    $checkbox = new DropdownCheckbox();
    expect($checkbox->classes())->toContain('ui-dropdown__item', 'ui-dropdown__item--checkbox');
});

it('dropdown checkbox generates name if not provided', function () {
    $checkbox = new DropdownCheckbox();
    expect($checkbox->name)->toStartWith('dd-check-');
});

it('dropdown checkbox respects provided name', function () {
    $checkbox = new DropdownCheckbox(name: 'my-check');
    expect($checkbox->name)->toBe('my-check');
});

it('dropdown submenu resolves default classes', function () {
    $submenu = new DropdownSubmenu();
    expect($submenu->classes())->toContain('ui-dropdown__item', 'ui-dropdown__item--submenu');
});
