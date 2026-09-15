<?php

namespace Rail\Ui\Tests\Unit;

use Rail\Ui\Components\Input;
use InvalidArgumentException;

it('resolves default classes for input', function () {
    $input = new Input();
    expect($input->classes())->toContain('ui-input', 'ui-input--outline', 'ui-input--md', 'ui-input--primary');
});

it('supports custom variants outline, soft, subtle, ghost, none', function (string $variant) {
    $input = new Input(variant: $variant);
    expect($input->classes())->toContain("ui-input--{$variant}");
})->with(['outline', 'soft', 'subtle', 'ghost', 'none']);

it('throws exception on invalid variant', function () {
    expect(fn () => (new Input(variant: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws exception on invalid size', function () {
    expect(fn () => (new Input(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws exception on invalid color', function () {
    expect(fn () => (new Input(color: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('resolves loading class when loading is true', function () {
    $input = new Input(loading: true);
    expect($input->classes())->toContain('ui-input--loading');
});

it('auto generates id from label', function () {
    $input = new Input(label: 'Email Address');
    expect($input->id)->toBe('ui-email-address');
    expect($input->name)->toBe('ui-email-address');
});
