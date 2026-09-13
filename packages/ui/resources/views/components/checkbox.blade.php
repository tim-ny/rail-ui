{{--
    @component  Checkbox
    @tag        <x-checkbox />
    @props      (see src/Components/Checkbox.php)
--}}
@props([
    'label'         => null,
    'hint'          => null,
    'error'         => null,
    'valid'         => false,
    'required'      => false,
    'readonly'      => false,
    'disabled'      => false,
    'checked'       => false,
    'value'         => null,
    'indeterminate' => false,
    'block'         => false,
    'unstyled'      => false,
    'wireModel'     => null,
])

@php
    $c = $component ?? null;
    if ($slot->isNotEmpty()) {
        $block = true;
        if ($c) $c->block = true;
    }
    $block = $c ? $c->block : $block;
    $hasErr = $c ? $c->hasError() : !empty($error);
    $isValid = $c ? $c->isValid() : ($valid && !$hasErr);
    $validationCls = $c ? $c->validationClass() : ($hasErr ? 'ui-field--error' : ($isValid ? 'ui-field--valid' : ''));
    $feedbackTxt = $c ? $c->feedbackText() : ($error ?? $hint ?? null);
    $feedbackCls = $c ? $c->feedbackClass() : ($hasErr ? 'ui-form-field__feedback--error' : 'ui-form-field__feedback--hint');
    $feedbackId = $c ? ($c->id . '-feedback') : null;
    $checkboxClasses = $c ? $c->classes() : 'ui-checkbox ui-checkbox--' . ($size ?? 'md') . ' ui-checkbox--' . ($color ?? 'primary') . ' ui-checkbox--radius-' . ($radius ?? 'sm') . ($block ? ' ui-checkbox--block' : '') . ($disabled ? ' ui-checkbox--disabled' : '') . ($readonly ? ' ui-checkbox--readonly' : '');
@endphp

<div class="{{ $unstyled ? '' : 'ui-form-field ui-form-field--inline ' . $validationCls }}">

    <div class="ui-checkbox__wrapper {{ $block ? 'ui-checkbox__wrapper--block' : '' }}">
        <input
            type="checkbox"
            class="ui-checkbox__input"
            {{ $attributes->merge([
                'id'               => $c ? $c->id : 'ui-checkbox-' . uniqid(),
                'name'             => $c ? $c->name : null,
                'value'            => $value,
                'checked'          => $checked ? 'checked' : null,
                'required'         => $required ? 'required' : null,
                'disabled'         => ($disabled || $readonly) ? 'disabled' : null,
                'aria-required'    => $required ? 'true' : null,
                'aria-invalid'     => $hasErr ? 'true' : null,
                'aria-describedby' => $feedbackTxt ? ($c ? $c->describedById() : 'ui-checkbox-' . uniqid() . '-feedback') : null,
                'class'            => $unstyled ? '' : $checkboxClasses,
            ]) }}
            @if($wireModel)
                {{ $c ? $c->wireModelAttribute() : "wire:model.lazy=\"{$wireModel}\"" }}
            @endif
            @if($indeterminate)
                x-data="{ uiIndeterminate: true }"
                x-init="$el.indeterminate = uiIndeterminate"
            @endif
        />

        @if($block)
            <label for="{{ $c ? $c->id : '' }}" class="ui-checkbox__label ui-checkbox__label--block">
                {{ $slot }}
            </label>
        @elseif($label ?? false)
            <label for="{{ $c ? $c->id : '' }}" class="ui-checkbox__label">
                <span class="ui-checkbox__box" aria-hidden="true">
                    <svg class="ui-checkbox__check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <svg class="ui-checkbox__dash" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" d="M6 12h12" />
                    </svg>
                </span>
                <span class="ui-checkbox__text">
                    {{ $label }}
                    @if($required)
                        <span class="ui-form-field__required" aria-hidden="true">*</span>
                    @endif
                </span>
            </label>
        @endif
    </div>

    @if($feedbackTxt)
        <p
            id="{{ $c ? $c->id : 'ui-checkbox-' . uniqid() }}-feedback"
            class="ui-form-field__feedback {{ $feedbackCls }}"
            @if($hasErr) role="alert" aria-live="polite" @endif
        >
            {{ $feedbackTxt }}
        </p>
    @endif

</div>
