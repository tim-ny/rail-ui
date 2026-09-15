<?php

namespace Rail\Ui\Tests\Unit;

use Rail\Ui\Components\Banner;
use InvalidArgumentException;

it('resolves default classes for banner', function () {
    $banner = new Banner();
    expect($banner->classes())->toContain('ui-banner', 'ui-banner--soft', 'ui-banner--md', 'ui-banner--primary', 'ui-banner--fixed');
});

it('supports custom variants solid, outline, soft, ghost', function (string $variant) {
    $banner = new Banner(variant: $variant);
    expect($banner->classes())->toContain("ui-banner--{$variant}");
})->with(['solid', 'outline', 'soft', 'ghost']);

it('throws exception on invalid variant', function () {
    expect(fn () => (new Banner(variant: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws exception on invalid size', function () {
    expect(fn () => (new Banner(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws exception on invalid color', function () {
    expect(fn () => (new Banner(color: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('does not include fixed class when fixed is false', function () {
    $banner = new Banner(fixed: false);
    expect($banner->classes())->not->toContain('ui-banner--fixed');
});

it('returns empty string when unstyled is true', function () {
    $banner = new Banner(unstyled: true);
    expect($banner->classes())->toBe('');
});

it('defaults closable to true', function () {
    $banner = new Banner();
    expect($banner->closable)->toBeTrue();
});

it('defaults showAfter to 300', function () {
    $banner = new Banner();
    expect($banner->showAfter)->toBe(300);
});

it('defaults autoHide to 0', function () {
    $banner = new Banner();
    expect($banner->autoHide)->toBe(0);
});

it('accepts custom showAfter value', function () {
    $banner = new Banner(showAfter: 500);
    expect($banner->showAfter)->toBe(500);
});

it('accepts custom autoHide value', function () {
    $banner = new Banner(autoHide: 5000);
    expect($banner->autoHide)->toBe(5000);
});
