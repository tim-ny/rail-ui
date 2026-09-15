{{--
    @component  DropdownHeader
    @tag        <x-dropdown-header />
    @props      (see src/Components/DropdownHeader.php)
--}}
@props([
    'unstyled' => false,
])

@php
    $c = $component ?? null;
    $headerClasses = $c ? $c->classes() : ($unstyled ? '' : 'ui-dropdown__header');
@endphp

<p {{ $attributes->merge(['class' => $headerClasses]) }} role="presentation">
    {{ $slot }}
</p>
