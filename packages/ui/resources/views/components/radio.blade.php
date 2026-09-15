{{--
    @component  Radio
    @tag        <x-radio />
    @props      (see src/Components/Radio.php)
--}}
@props([
    'value'       => null,
    'label'       => null,
    'description' => null,
    'disabled'    => false,
    'unstyled'    => false,
])

@php
    $c = $component ?? null;
    $radioValue = $c ? $c->value : $value;
    $radioLabel = $c ? $c->label : $label;
    $radioDesc = $c ? $c->description : $description;
    $isDisabled = $c ? $c->disabled : $disabled;
    $itemClasses = $c ? $c->classes() : ($unstyled ? '' : 'ui-radio' . ($isDisabled ? ' ui-radio--disabled' : ''));
    $radioId = 'ui-radio-' . ($radioValue ? \Illuminate\Support\Str::slug($radioValue) : uniqid());
@endphp

<label
    {{ $attributes->merge(['class' => $itemClasses]) }}
    for="{{ $radioId }}"
>
    <input
        type="radio"
        class="ui-radio__input"
        name="{{ $attributes->get('name') ?? 'radio' }}"
        value="{{ $radioValue }}"
        :checked="selected === @js($radioValue)"
        @click="selected = @js($radioValue)"
        @if($isDisabled) disabled @endif
        id="{{ $radioId }}"
    />

    <span class="ui-radio__circle" aria-hidden="true">
        <span class="ui-radio__dot"></span>
    </span>

    @if($slot->isNotEmpty() || $radioLabel || $radioDesc)
        <span class="ui-radio__content">
            @if($slot->isNotEmpty())
                {{ $slot }}
            @else
                <span class="ui-radio__label">{{ $radioLabel }}</span>
                @if($radioDesc)
                    <span class="ui-radio__description">{{ $radioDesc }}</span>
                @endif
            @endif
        </span>
    @endif
</label>
