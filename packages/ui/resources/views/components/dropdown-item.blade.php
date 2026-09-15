{{--
    @component  DropdownItem
    @tag        <x-dropdown-item />
    @props      (see src/Components/DropdownItem.php)
--}}
@props([
    'href'      => null,
    'icon'      => null,
    'shortcut'  => null,
    'danger'    => false,
    'disabled'  => false,
    'unstyled'  => false,
])

@php
    $c = $component ?? null;
    $itemClasses = $c ? $c->classes() : ($unstyled ? '' : 'ui-dropdown__item' . ($danger ? ' ui-dropdown__item--danger' : '') . ($disabled ? ' ui-dropdown__item--disabled' : ''));
@endphp

@if($href && !$disabled)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $itemClasses]) }}
        role="menuitem"
        @click="$dispatch('dropdown-close')"
    >
        @if($icon)
            <x-icon :name="$icon" size="sm" class="ui-dropdown__icon" aria-hidden="true" />
        @endif
        <span class="ui-dropdown__label">{{ $slot }}</span>
        @if($shortcut)
            <span class="ui-dropdown__shortcut">{{ $shortcut }}</span>
        @endif
    </a>
@else
    <button
        type="button"
        {{ $attributes->merge(['class' => $itemClasses]) }}
        role="menuitem"
        @if($disabled) disabled aria-disabled="true" @endif
        @click="!{{ $disabled ? 'true' : 'false' }} && $dispatch('dropdown-close')"
    >
        @if($icon)
            <x-icon :name="$icon" size="sm" class="ui-dropdown__icon" aria-hidden="true" />
        @endif
        <span class="ui-dropdown__label">{{ $slot }}</span>
        @if($shortcut)
            <span class="ui-dropdown__shortcut">{{ $shortcut }}</span>
        @endif
    </button>
@endif
