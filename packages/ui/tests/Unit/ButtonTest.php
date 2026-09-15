<?php

namespace Rail\Ui\Tests\Unit;

use Rail\Ui\Components\Button;
use InvalidArgumentException;

it('resolves solid primary md classes by default', function () {
    $button = new Button();
    expect($button->classes())->toContain('ui-btn', 'ui-btn--solid', 'ui-btn--primary', 'ui-btn--md');
});

it('supports solid outline ghost soft variants', function (string $variant) {
    $button = new Button(variant: $variant);
    expect($button->classes())->toContain("ui-btn--{$variant}");
})->with(['solid', 'outline', 'ghost', 'soft']);

it('throws on invalid variant', function () {
    expect(fn () => (new Button(variant: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws on invalid size', function () {
    expect(fn () => (new Button(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws on invalid color', function () {
    expect(fn () => (new Button(color: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('resolves tag to a when href is set', function () {
    $button = new Button(href: 'https://example.com');
    expect($button->resolveTag())->toBe('a');
    expect($button->resolveType())->toBeNull();
});

it('resolves target _blank when external is true', function () {
    $button = new Button(href: 'https://example.com', external: true);
    expect($button->resolveTarget())->toBe('_blank');
});

it('applies loading class when loading is true', function () {
    $button = new Button(loading: true);
    expect($button->classes())->toContain('ui-btn--loading');
});
