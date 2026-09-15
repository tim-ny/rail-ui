{{--
    @component  RadioGroup
    @tag        <x-radio-group />
    @props      (see src/Components/RadioGroup.php)
--}}
@props([
    'value'    => null,
    'label'    => null,
    'disabled' => false,
    'size'     => 'md',
    'color'    => 'primary',
    'unstyled' => false,
    'hint'     => null,
    'error'    => null,
    'valid'    => false,
    'required' => false,
    'wireModel' => null,
])

@php
    $c = $component ?? null;
    $groupId = $c ? $c->id : ('ui-radio-group-' . uniqid());
    $groupName = $c ? $c->name : $groupId;
    $hasErr = $c ? $c->hasError() : !empty($error);
    $isValid = $c ? $c->isValid() : ($valid && !$hasErr);
    $validationCls = $c ? $c->validationClass() : ($hasErr ? 'ui-field--error' : ($isValid ? 'ui-field--valid' : ''));
    $feedbackTxt = $c ? $c->feedbackText() : ($error ?? $hint ?? null);
    $feedbackCls = $c ? $c->feedbackClass() : ($hasErr ? 'ui-form-field__feedback--error' : 'ui-form-field__feedback--hint');
    $groupClasses = $c ? $c->classes() : 'ui-radio-group ui-radio-group--' . ($size ?? 'md') . ' ui-radio-group--' . ($color ?? 'primary') . ($disabled ? ' ui-radio-group--disabled' : '');
    $selectedValue = $c ? $c->value : $value;
@endphp

<div
    x-data="{ selected: @js($selectedValue) }"
    {{ $attributes->merge(['class' => $unstyled ? '' : $validationCls]) }}
>
    @if($label)
        <span class="{{ $unstyled ? '' : 'ui-radio-group__label' }}">
            {{ $label }}
            @if($required)
                <span class="ui-form-field__required" aria-hidden="true">*</span>
            @endif
        </span>
    @endif

    <div
        class="{{ $groupClasses }}"
        role="radiogroup"
        aria-required="{{ $required ? 'true' : 'false' }}"
        aria-invalid="{{ $hasErr ? 'true' : 'false' }}"
        @if($feedbackTxt) aria-describedby="{{ $groupId }}-feedback" @endif
    >
        @if($wireModel)
            @if($c)
                {!! $c->wireModelAttribute() !!}
            @else
                wire:model.lazy="{{ $wireModel }}"
            @endif
        @endif
        {{ $slot }}
    </div>

    @if($feedbackTxt)
        <p
            id="{{ $groupId }}-feedback"
            class="ui-form-field__feedback {{ $feedbackCls }}"
            @if($hasErr) role="alert" aria-live="polite" @endif
        >
            {{ $feedbackTxt }}
        </p>
    @endif
</div>
