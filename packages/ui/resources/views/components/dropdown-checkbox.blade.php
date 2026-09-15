{{--
    @component  DropdownCheckbox
    @tag        <x-dropdown-checkbox />
    @props      (see src/Components/DropdownCheckbox.php)
--}}
@props([
    'label'    => null,
    'name'     => null,
    'checked'  => false,
    'disabled' => false,
    'unstyled' => false,
])

@php
    $c = $component ?? null;
    $checkName = $c ? $c->name : ($name ?? 'dd-check-' . uniqid());
    $isChecked = $c ? $c->checked : $checked;
    $isDisabled = $c ? $c->disabled : $disabled;
    $itemClasses = $c ? $c->classes() : ($unstyled ? '' : 'ui-dropdown__item ui-dropdown__item--checkbox' . ($isDisabled ? ' ui-dropdown__item--disabled' : ''));
@endphp

<button
    type="button"
    {{ $attributes->merge(['class' => $itemClasses]) }}
    role="menuitemcheckbox"
    :aria-checked="{{ $isChecked ? 'true' : 'false' }}"
    @if($isDisabled) disabled aria-disabled="true" @endif
    x-data="{ checked: {{ $isChecked ? 'true' : 'false' }} }"
    @click.stop="checked = !checked"
>
    <span class="ui-dropdown__check-icon">
        <x-icon name="check" size="sm" class="ui-dropdown__checkmark" x-show="checked" aria-hidden="true" />
    </span>
    <span class="ui-dropdown__label">{{ $label ?? $slot }}</span>
</button>
