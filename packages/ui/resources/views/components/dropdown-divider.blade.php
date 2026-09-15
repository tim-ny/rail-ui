{{--
    @component  DropdownDivider
    @tag        <x-dropdown-divider />
    @props      (see src/Components/DropdownDivider.php)
--}}
@props([
    'unstyled' => false,
])

@php
    $c = $component ?? null;
    $dividerClasses = $c ? $c->classes() : ($unstyled ? '' : 'ui-dropdown__divider');
@endphp

<hr {{ $attributes->merge(['class' => $dividerClasses, 'role' => 'separator']) }} />
