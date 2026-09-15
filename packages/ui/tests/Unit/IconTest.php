<?php

namespace Rail\Ui\Tests\Unit;

use Rail\Ui\Components\Icon;
use InvalidArgumentException;

it('normalises icon name by stripping tabler prefix', function () {
    $icon = new Icon(name: 'tabler-eye');
    expect($icon->name)->toBe('eye');
});

it('resolves size class correctly', function () {
    $icon = new Icon(name: 'search', size: 'lg');
    expect($icon->sizeClass())->toBe('ui-icon--lg');
});

it('throws exception on invalid size', function () {
    expect(fn () => (new Icon(name: 'search', size: 'invalid'))->sizeClass())->toThrow(InvalidArgumentException::class);
});
