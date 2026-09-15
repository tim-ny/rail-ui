<?php

namespace Rail\Ui\Tests\Unit;

use Rail\Ui\Components\Spinner;
use InvalidArgumentException;

it('resolves correct size classes for spinner', function () {
    $spinner = new Spinner(size: 'sm');
    expect($spinner->sizeClass())->toBe('ui-spinner--sm');
});

it('throws exception on invalid spinner size', function () {
    expect(fn () => (new Spinner(size: 'invalid'))->sizeClass())->toThrow(InvalidArgumentException::class);
});

it('resolves default loading icon from config', function () {
    config()->set('rail-ui.loading.icon', 'loader-2');

    expect((new Spinner)->resolveIcon())->toBe('loader-2');
});

it('falls back to loader icon when no config is set', function () {
    config()->set('rail-ui.loading.icon', null);

    expect((new Spinner)->resolveIcon())->toBe('loader');
});

it('prefers the icon prop over config', function () {
    config()->set('rail-ui.loading.icon', 'loader');

    expect((new Spinner(icon: 'refresh'))->resolveIcon())->toBe('refresh');
});

it('strips ti and tabler prefixes from icon names', function () {
    expect((new Spinner(icon: 'ti-loader'))->resolveIcon())->toBe('loader');
    expect((new Spinner(icon: 'tabler-refresh'))->resolveIcon())->toBe('refresh');
});

it('throws exception on empty spinner icon', function () {
    config()->set('rail-ui.loading.icon', '  ');

    expect(fn () => (new Spinner)->resolveIcon())->toThrow(InvalidArgumentException::class);
});

it('uses the tabler icon by default when icons are enabled', function () {
    config()->set('rail-ui.features.icons', true);

    expect((new Spinner)->usesTablerIcon())->toBeTrue();
});

it('falls back to the built-in svg when icons are disabled', function () {
    config()->set('rail-ui.features.icons', false);

    expect((new Spinner)->usesTablerIcon())->toBeFalse();
});
