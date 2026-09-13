{{--
    @component  Textarea
    @tag        <x-textarea />
    @props      (see src/Components/Textarea.php)
--}}
@props([
    'rows'              => '3',
    'maxlength'         => null,
    'autoResize'        => false,
    'label'             => null,
    'hint'              => null,
    'error'             => null,
    'valid'             => false,
    'required'          => false,
    'readonly'          => false,
    'disabled'          => false,
    'loading'           => false,
    'block'             => false,
    'id'                => null,
    'name'              => null,
    'placeholder'       => null,
    'autocomplete'      => null,
    'autofocus'         => false,
    'leadingIcon'       => null,
    'trailingIcon'      => null,
    'unstyled'          => false,
    'wireModel'         => null,
])

@php
    $c = $component ?? null;
    $textareaId = $c ? $c->id : ($id ?? ($label ? 'ui-' . \Illuminate\Support\Str::slug($label) : 'ui-' . uniqid()));
    $textareaName = $c ? $c->name : ($name ?? $textareaId);
    $textareaRows = $c ? $c->rows : $rows;
    $textareaAutoResize = $c ? $c->autoResize : $autoResize;
    $hasErr = $c ? $c->hasError() : !empty($error);
    $isVld = $c ? $c->isValid() : ($valid && !$hasErr);
    $feedbackTxt = $c ? $c->feedbackText() : ($error ?? $hint ?? null);
    $validationCls = $c ? $c->validationClass() : ($hasErr ? 'ui-field--error' : ($isVld ? 'ui-field--valid' : ($readonly ? 'ui-field--readonly' : '')));
    $feedbackCls = $c ? $c->feedbackClass() : ($hasErr ? 'ui-field__feedback--error' : 'ui-field__feedback--hint');
    $describedBy = $c ? $c->describedById() : ($feedbackTxt ? "{$textareaId}-feedback" : null);
    $textareaClasses = $unstyled ? '' : ($c ? $c->classes() : 'ui-textarea ui-textarea--outline ui-textarea--md ui-textarea--primary');
@endphp

<div class="{{ $unstyled ? '' : 'ui-form-field ' . $validationCls }}">

    {{-- Label --}}
    @if($label ?? false)
        <label for="{{ $textareaId }}" class="ui-form-field__label">
            {{ $label }}
            @if($required)
                <span class="ui-form-field__required" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    @if($textareaAutoResize)
        <div
            x-data="{
                resize() {
                    $el.querySelector('textarea').style.height = 'auto';
                    $el.querySelector('textarea').style.height = $el.querySelector('textarea').scrollHeight + 'px';
                }
            }"
            x-init="$nextTick(() => resize())"
            @input.window="if($event.target.closest('.ui-form-field') === $el) resize()"
        >
    @endif

    <textarea
        {{ $attributes->merge([
            'id'               => $textareaId,
            'name'             => $textareaName,
            'placeholder'      => $placeholder,
            'autocomplete'     => $autocomplete,
            'autofocus'        => $autofocus ? 'autofocus' : null,
            'rows'             => $textareaRows,
            'maxlength'        => $maxlength,
            'readonly'         => $readonly ? 'readonly' : null,
            'disabled'         => ($disabled || $loading) ? 'disabled' : null,
            'required'         => $required ? 'required' : null,
            'aria-required'    => $required ? 'true' : null,
            'aria-invalid'     => $hasErr ? 'true' : null,
            'aria-busy'        => $loading ? 'true' : null,
            'aria-describedby' => $describedBy,
            'class'            => $textareaClasses . ($textareaAutoResize ? ' ui-textarea--auto-resize' : ''),
        ]) }}
        @if($textareaAutoResize) @input.window="resize()" @endif
        @if($wireModel)
            {{ $c ? $c->wireModelAttribute() : "wire:model.lazy=\"{$wireModel}\"" }}
            wire:loading.attr="aria-busy"
            wire:target="{{ $wireModel }}"
        @endif
    ></textarea>

    @if($textareaAutoResize)
        </div>
    @endif

    {{-- Loading spinner --}}
    @if($loading)
        <div class="ui-textarea__loading" aria-hidden="true">
            <x-spinner size="xs" />
        </div>
    @endif

    {{-- Feedback (error or hint) --}}
    @if($feedbackTxt)
        <p
            id="{{ $textareaId }}-feedback"
            class="ui-form-field__feedback {{ $feedbackCls }}"
            @if($hasErr) role="alert" aria-live="polite" @endif
        >
            {{ $feedbackTxt }}
        </p>
    @endif

    {{-- Character count --}}
    @if($maxlength)
        <p
            class="ui-form-field__charcount"
            x-data="{ count: $el.closest('.ui-form-field').querySelector('textarea').value.length }"
            x-text="count + ' / {{ $maxlength }}'"
            @input.window="count = $event.target.closest('.ui-form-field')?.querySelector('textarea')?.value?.length ?? count"
            aria-live="polite"
            aria-atomic="true"
        ></p>
    @endif

</div>
