{{--
    @component  DropdownSubmenu
    @tag        <x-dropdown-submenu />
    @props      (see src/Components/DropdownSubmenu.php)
--}}
@props([
    'label'    => null,
    'icon'     => null,
    'disabled' => false,
    'unstyled' => false,
])

@php
    $c = $component ?? null;
    $itemClasses = $c ? $c->classes() : ($unstyled ? '' : 'ui-dropdown__item ui-dropdown__item--submenu' . ($disabled ? ' ui-dropdown__item--disabled' : ''));
@endphp

<div
    {{ $attributes->merge(['class' => 'ui-dropdown__submenu-wrap']) }}
    x-data="{ subOpen: false }"
    @mouseenter="subOpen = true"
    @mouseleave="subOpen = false"
>
    <button
        type="button"
        {{ $attributes->merge(['class' => $itemClasses, 'style' => '']) }}
        role="menuitem"
        :aria-expanded="subOpen ? 'true' : 'false'"
        @if($disabled) disabled aria-disabled="true" @endif
    >
        @if($icon)
            <x-icon :name="$icon" size="sm" class="ui-dropdown__icon" aria-hidden="true" />
        @endif
        <span class="ui-dropdown__label">{{ $label ?? $slot }}</span>
        <x-icon name="chevron-right" size="sm" class="ui-dropdown__chevron-sub" aria-hidden="true" />
    </button>

    <div
        x-show="subOpen"
        x-cloak
        x-transition:enter="ui-dropdown__menu--enter"
        x-transition:enter-start="ui-dropdown__submenu--enter-start"
        x-transition:enter-end="ui-dropdown__menu--enter-end"
        x-transition:leave="ui-dropdown__menu--leave"
        x-transition:leave-start="ui-dropdown__menu--leave-start"
        x-transition:leave-end="ui-dropdown__menu--leave-end"
        class="ui-dropdown__submenu"
        role="menu"
    >
        {{ $slot }}
    </div>
</div>
