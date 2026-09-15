{{--
    @component  Toggle
    @tag        <x-toggle />
    @props      (see src/Components/Toggle.php)
--}}
@props([
    'label'      => null,
    'checked'    => false,
    'disabled'   => false,
    'size'       => 'md',
    'color'      => 'primary',
    'unstyled'   => false,
    'hint'       => null,
    'error'      => null,
    'valid'      => false,
    'required'   => false,
    'wireModel'  => null,
])

@php
    $c = $component ?? null;
    $toggleId = $c ? $c->id : 'ui-toggle-' . ($label ? \Illuminate\Support\Str::slug($label) : uniqid());
    $toggleName = $c ? $c->name : $toggleId;
    $hasErr = $c ? $c->hasError() : !empty($error);
    $isValid = $c ? $c->isValid() : ($valid && !$hasErr);
    $validationCls = $c ? $c->validationClass() : ($hasErr ? 'ui-field--error' : ($isValid ? 'ui-field--valid' : ''));
    $feedbackTxt = $c ? $c->feedbackText() : ($error ?? $hint ?? null);
    $feedbackCls = $c ? $c->feedbackClass() : ($hasErr ? 'ui-form-field__feedback--error' : 'ui-form-field__feedback--hint');
    $toggleClasses = $c ? $c->classes() : 'ui-toggle ui-toggle--' . ($size ?? 'md') . ' ui-toggle--' . ($color ?? 'primary') . ($disabled ? ' ui-toggle--disabled' : '');
@endphp

<div class="{{ $unstyled ? '' : $validationCls }}">
    <div
        x-data="{ on: {{ $checked ? 'true' : 'false' }} }"
        {{ $attributes->merge(['class' => $unstyled ? '' : 'ui-toggle__wrapper']) }}
    >
        <input
            type="checkbox"
            class="ui-toggle__input"
            name="{{ $toggleName }}"
            id="{{ $toggleId }}"
            :checked="on"
            @click="on = !on"
            @if($disabled) disabled @endif
            @if($required) required aria-required="true" @endif
            aria-invalid="{{ $hasErr ? 'true' : 'false' }}"
            @if($feedbackTxt) aria-describedby="{{ $toggleId }}-feedback" @endif
            @if($wireModel)
                @if($c)
                    {!! $c->wireModelAttribute() !!}
                @else
                    wire:model.lazy="{{ $wireModel }}"
                @endif
            @endif
        />

        <button
            type="button"
            role="switch"
            :aria-checked="on ? 'true' : 'false'"
            @click="on = !on"
            @if($disabled) disabled aria-disabled="true" @endif
            class="{{ $toggleClasses }}"
        >
            <span :class="on ? 'ui-toggle__knob--on' : 'ui-toggle__knob--off'" class="ui-toggle__knob"></span>
        </button>

        @if($label || $slot->isNotEmpty())
            <label for="{{ $toggleId }}" class="{{ $unstyled ? '' : 'ui-toggle__label' }}">
                {{ $label ?? $slot }}
                @if($required)
                    <span class="ui-form-field__required" aria-hidden="true">*</span>
                @endif
            </label>
        @endif
    </div>

    @if($feedbackTxt)
        <p
            id="{{ $toggleId }}-feedback"
            class="ui-form-field__feedback {{ $feedbackCls }}"
            @if($hasErr) role="alert" aria-live="polite" @endif
        >
            {{ $feedbackTxt }}
        </p>
    @endif
</div>
