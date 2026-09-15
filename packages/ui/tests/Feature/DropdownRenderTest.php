<?php

namespace Rail\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;


it('renders dropdown container with trigger', function () {
    $blade = <<<'BLADE'
<x-dropdown trigger="Actions">
    <x-dropdown-item href="/profile" icon="user">Profile</x-dropdown-item>
</x-dropdown>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('x-data="{ open: false }"')
        ->toContain('ui-dropdown')
        ->toContain('Actions')
        ->toContain('Profile');
});

it('renders dropdown with header and divider', function () {
    $blade = <<<'BLADE'
<x-dropdown trigger="Menu">
    <x-dropdown-header>Account</x-dropdown-header>
    <x-dropdown-item href="/profile">Profile</x-dropdown-item>
    <x-dropdown-divider />
    <x-dropdown-item href="/logout" danger>Sign out</x-dropdown-item>
</x-dropdown>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-dropdown__header')
        ->toContain('Account')
        ->toContain('ui-dropdown__divider')
        ->toContain('ui-dropdown__item--danger');
});

it('renders dropdown checkbox items', function () {
    $blade = <<<'BLADE'
<x-dropdown trigger="Options">
    <x-dropdown-checkbox name="bookmarks" :checked="true">Show bookmarks</x-dropdown-checkbox>
    <x-dropdown-checkbox name="urls" :checked="false">Show URLs</x-dropdown-checkbox>
</x-dropdown>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-dropdown__item--checkbox')
        ->toContain('role="menuitemcheckbox"')
        ->toContain('Show bookmarks')
        ->toContain('Show URLs');
});

it('renders dropdown submenu', function () {
    $blade = <<<'BLADE'
<x-dropdown trigger="Share">
    <x-dropdown-item icon="link">Copy link</x-dropdown-item>
    <x-dropdown-submenu label="Share via" icon="share-2">
        <x-dropdown-item icon="brand-x">Twitter</x-dropdown-item>
    </x-dropdown-submenu>
</x-dropdown>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-dropdown__submenu-wrap')
        ->toContain('Share via')
        ->toContain('ui-dropdown__submenu');
});

it('renders dropdown with alignment', function () {
    $blade = <<<'BLADE'
<x-dropdown trigger="Menu" align="right">
    <x-dropdown-item href="/action">Action</x-dropdown-item>
</x-dropdown>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)->toContain('ui-dropdown--right');
});

it('renders dropdown with size and variant', function () {
    $blade = <<<'BLADE'
<x-dropdown trigger="Menu" size="lg" variant="outline" color="danger">
    <x-dropdown-item>Item</x-dropdown-item>
</x-dropdown>
BLADE;

    $rendered = Blade::render($blade);

    expect($rendered)
        ->toContain('ui-btn--lg')
        ->toContain('ui-btn--outline')
        ->toContain('ui-btn--danger');
});
