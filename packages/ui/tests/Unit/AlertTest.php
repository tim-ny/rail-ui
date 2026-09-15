<?php

namespace Rail\Ui\Tests\Unit;

use Rail\Ui\Components\Alert;
use InvalidArgumentException;

it('resolves soft primary md classes by default for alert', function () {
    $alert = new Alert();
    expect($alert->classes())->toContain('ui-alert', 'ui-alert--soft', 'ui-alert--primary', 'ui-alert--md');
});

it('supports soft outline solid ghost variants', function (string $variant) {
    $alert = new Alert(variant: $variant);
    expect($alert->classes())->toContain("ui-alert--{$variant}");
})->with(['soft', 'outline', 'solid', 'ghost']);

it('throws exception on invalid alert variant', function () {
    expect(fn () => (new Alert(variant: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws exception on invalid alert size', function () {
    expect(fn () => (new Alert(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('resolves fallback icons based on color theme', function (string $color, string $expectedIcon) {
    $alert = new Alert(color: $color);
    expect($alert->resolveIcon())->toBe($expectedIcon);
})->with([
    ['danger', 'alert-circle'],
    ['warning', 'alert-triangle'],
    ['success', 'circle-check'],
    ['primary', 'info-circle'],
    ['neutral', 'info-circle'],
]);

it('resolves ARIA role based on color', function (string $color, string $expectedRole) {
    $alert = new Alert(color: $color);
    expect($alert->resolveRole())->toBe($expectedRole);
})->with([
    ['danger', 'alert'],
    ['warning', 'alert'],
    ['success', 'status'],
    ['primary', 'status'],
    ['neutral', 'status'],
]);

it('allows suppressing icon with none', function () {
    $alert = new Alert(icon: 'none');
    expect($alert->resolveIcon())->toBeNull();
});
