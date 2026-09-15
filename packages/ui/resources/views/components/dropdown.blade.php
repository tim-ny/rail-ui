{{--
    @component  Dropdown
    @tag        <x-dropdown />
    @props      (see src/Components/Dropdown.php)
--}}
@props([
    'trigger'  => 'Open',
    'align'    => 'left',
    'size'     => 'md',
    'variant'  => 'solid',
    'color'    => 'primary',
    'unstyled' => false,
])

@php
    $c = $component ?? null;
    $dropdownId = 'ui-dd-' . \Illuminate\Support\Str::random(8);
    $menuAlign = $c ? $c->align : $align;
@endphp

<div
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
    {{ $attributes->merge([
        'class' => $c ? $c->classes() : ($unstyled ? '' : 'ui-dropdown ui-dropdown--' . $menuAlign),
    ]) }}
>
    {{-- Trigger --}}
    @if(isset($triggerSlot))
        <div @click="open = !open">
            {{ $triggerSlot }}
        </div>
    @else
        <button
            type="button"
            @click="open = !open"
            class="{{ $unstyled ? '' : "ui-dropdown__trigger ui-btn ui-btn--{$variant} ui-btn--{$size} ui-btn--{$color}" }}"
            :aria-expanded="open ? 'true' : 'false'"
            aria-haspopup="true"
        >
            {{ $trigger ?? 'Open' }}
            <x-icon name="chevron-down" size="sm" class="ui-dropdown__chevron" ::class="{ 'ui-dropdown__chevron--open': open }" aria-hidden="true" />
        </button>
    @endif

    {{-- Menu --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="ui-dropdown__menu--enter"
        x-transition:enter-start="ui-dropdown__menu--enter-start"
        x-transition:enter-end="ui-dropdown__menu--enter-end"
        x-transition:leave="ui-dropdown__menu--leave"
        x-transition:leave-start="ui-dropdown__menu--leave-start"
        x-transition:leave-end="ui-dropdown__menu--leave-end"
        @click.away="open = false"
        class="{{ $unstyled ? '' : 'ui-dropdown__menu' }}"
        role="menu"
        aria-label="{{ $trigger ?? 'Menu' }}"
    >
        {{ $slot }}
    </div>
</div>
