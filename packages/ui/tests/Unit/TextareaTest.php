<?php

namespace Aegis\Ui\Tests\Unit;

use Aegis\Ui\Components\Textarea;
use InvalidArgumentException;

it('resolves default classes for textarea', function () {
    $textarea = new Textarea();
    expect($textarea->classes())->toContain('ui-textarea', 'ui-textarea--outline', 'ui-textarea--md', 'ui-textarea--primary');
});

it('supports custom variants outline, soft, subtle, ghost, none', function (string $variant) {
    $textarea = new Textarea(variant: $variant);
    expect($textarea->classes())->toContain("ui-textarea--{$variant}");
})->with(['outline', 'soft', 'subtle', 'ghost', 'none']);

it('throws exception on invalid variant', function () {
    expect(fn () => (new Textarea(variant: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws exception on invalid size', function () {
    expect(fn () => (new Textarea(size: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('throws exception on invalid color', function () {
    expect(fn () => (new Textarea(color: 'invalid'))->classes())->toThrow(InvalidArgumentException::class);
});

it('resolves loading class when loading is true', function () {
    $textarea = new Textarea(loading: true);
    expect($textarea->classes())->toContain('ui-textarea--loading');
});

it('auto generates id from label', function () {
    $textarea = new Textarea(label: 'About You');
    expect($textarea->id)->toBe('ui-about-you');
    expect($textarea->name)->toBe('ui-about-you');
});

it('returns empty string when unstyled is true', function () {
    $textarea = new Textarea(unstyled: true);
    expect($textarea->classes())->toBe('');
});

it('defaults to 3 rows', function () {
    $textarea = new Textarea();
    expect($textarea->rows)->toBe('3');
});

it('accepts custom rows value', function () {
    $textarea = new Textarea(rows: '8');
    expect($textarea->rows)->toBe('8');
});

it('defaults autoResize to false', function () {
    $textarea = new Textarea();
    expect($textarea->autoResize)->toBeFalse();
});

it('enables autoResize when set to true', function () {
    $textarea = new Textarea(autoResize: true);
    expect($textarea->autoResize)->toBeTrue();
});
