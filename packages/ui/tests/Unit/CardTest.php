<?php

namespace Rail\Ui\Tests\Unit;

use Rail\Ui\Components\Card;

it('resolves default classes for card', function () {
    $card = new Card();
    expect($card->classes())->toContain('ui-card', 'ui-card--padding-md', 'ui-card--shadow-sm', 'ui-card--border');
});

it('supports custom padding none, sm, md, lg', function (string $padding) {
    $card = new Card(padding: $padding);
    expect($card->classes())->toContain("ui-card--padding-{$padding}");
})->with(['none', 'sm', 'md', 'lg']);

it('supports custom shadow none, sm, md, lg', function (string $shadow) {
    $card = new Card(shadow: $shadow);
    expect($card->classes())->toContain("ui-card--shadow-{$shadow}");
})->with(['none', 'sm', 'md', 'lg']);

it('does not include border class when border is false', function () {
    $card = new Card(border: false);
    expect($card->classes())->not->toContain('ui-card--border');
});

it('includes overflow class when overflow is true', function () {
    $card = new Card(overflow: true);
    expect($card->classes())->toContain('ui-card--overflow');
});

it('includes link class when href is provided', function () {
    $card = new Card(href: '/about');
    expect($card->classes())->toContain('ui-card--link');
});

it('returns empty string when unstyled is true', function () {
    $card = new Card(unstyled: true);
    expect($card->classes())->toBe('');
});
