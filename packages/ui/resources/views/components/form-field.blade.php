{{--
    @component  FormField
    @tag        <x-form-field label="Label" error="Error message" required />
    @props      (see src/Components/FormField.php)
--}}
@props([
    'label'    => null,
    'hint'     => null,
    'error'    => null,
    'valid'    => false,
    'required' => false,
    'readonly' => false,
    'id'       => null,
    'name'     => null,
    'unstyled' => false,
])

@php
    $c = $component ?? null;
    $fieldId = $c ? $c->id : ($id ?? 'ui-field-' . uniqid());
@endphp

<div {{ $attributes->merge(['class' => $unstyled ? '' : ($c ? $c->classes() : 'ui-form-field')]) }}>

    {{-- Label --}}
    @if(isset($labelSlot) || ($label ?? false))
        <label for="{{ $fieldId }}" class="ui-form-field__label">
            {{ $labelSlot ?? $label }}
            @if($required)
                <span class="ui-form-field__required" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    {{-- Slot (Input control) --}}
    {{ $slot }}

    {{-- Feedback (error or hint) --}}
    @if(isset($errorSlot) || isset($hintSlot) || ($c ? $c->feedbackText() : ($error ?? $hint ?? null)))
        @php
            $feedbackTxt = $errorSlot ?? $hintSlot ?? ($c ? $c->feedbackText() : ($error ?? $hint ?? null));
            $hasErr = $c ? $c->hasError() : !empty($error);
            $feedbackCls = $c ? $c->feedbackClass() : ($hasErr ? 'ui-field__feedback--error' : 'ui-field__feedback--hint');
        @endphp
        <p
            id="{{ $fieldId }}-feedback"
            class="ui-form-field__feedback {{ $feedbackCls }}"
            @if($hasErr) role="alert" aria-live="polite" @endif
        >
            {{ $feedbackTxt }}
        </p>
    @endif

</div>
