<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;

it('renders banner with title and description', function () {
    $rendered = Blade::render('<x-banner title="New Feature" href="#">Click here to learn more</x-banner>');

    expect($rendered)
        ->toContain('ui-banner')
        ->toContain('New Feature')
        ->toContain('Click here to learn more')
        ->toContain('role="banner"');
});

it('renders close button when closable is true', function () {
    $rendered = Blade::render('<x-banner title="Notice" closable>Dismiss me</x-banner>');

    expect($rendered)
        ->toContain('ui-banner__close')
        ->toContain('aria-label="Dismiss banner"')
        ->toContain('@click="visible = false"');
});

it('does not render close button when closable is false', function () {
    $rendered = Blade::render('<x-banner title="Notice" :closable="false">Important</x-banner>');

    expect($rendered)
        ->not->toContain('ui-banner__close');
});

it('renders link wrapper when href is provided', function () {
    $rendered = Blade::render('<x-banner title="Announcement" href="https://example.com">Read more</x-banner>');

    expect($rendered)
        ->toContain('<a href="https://example.com"')
        ->toContain('ui-banner__content');
});

it('renders div wrapper when no href', function () {
    $rendered = Blade::render('<x-banner title="Notice">Info</x-banner>');

    expect($rendered)
        ->toContain('<div class="ui-banner__content">')
        ->not->toContain('<a href');
});

it('renders icon when icon prop is set', function () {
    $rendered = Blade::render('<x-banner title="Update" icon="sparkles">New features available</x-banner>');

    expect($rendered)
        ->toContain('ui-banner__icon');
});

it('renders with custom showAfter delay', function () {
    $rendered = Blade::render('<x-banner title="Delayed" :show-after="1000">Shows after 1s</x-banner>');

    expect($rendered)
        ->toContain('setTimeout')
        ->toContain('1000');
});

it('renders autoHide timeout when set', function () {
    $rendered = Blade::render('<x-banner title="Auto-hide" :auto-hide="5000">Gone in 5s</x-banner>');

    expect($rendered)
        ->toContain('setTimeout')
        ->toContain('5300');
});

it('renders slot content as description', function () {
    $rendered = Blade::render('<x-banner title="Tip">This is the description text</x-banner>');

    expect($rendered)
        ->toContain('ui-banner__description')
        ->toContain('This is the description text');
});

it('renders with action slot', function () {
    $rendered = Blade::render('<x-banner title="Update">
        <x-slot:action><button class="learn-more">Learn more</button></x-slot:action>
        New version available
    </x-banner>');

    expect($rendered)
        ->toContain('ui-banner__action')
        ->toContain('Learn more');
});

it('defaults to fixed positioning', function () {
    $rendered = Blade::render('<x-banner title="Fixed">Content</x-banner>');

    expect($rendered)
        ->toContain('ui-banner--fixed');
});

it('renders non-fixed when fixed is false', function () {
    $rendered = Blade::render('<x-banner title="Static" :fixed="false">Content</x-banner>');

    expect($rendered)
        ->not->toContain('ui-banner--fixed');
});
