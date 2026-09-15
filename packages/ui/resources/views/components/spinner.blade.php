{{--
    @component  Spinner
    @tag        <x-spinner size="md" label="Loading" />
    @props      (see src/Components/Spinner.php)
--}}
@props(['size' => 'md', 'color' => null, 'label' => 'Loading', 'icon' => null])

@php
    $c = $component ?? null;
    $spinnerSizeClass = $c ? $c->sizeClass() : 'ui-spinner--' . ($size ?? 'md');
    $spinnerClasses = 'ui-spinner ' . $spinnerSizeClass . ($color ? " text-{$color}" : '');
    $usesTabler = $c ? $c->usesTablerIcon() : config('rail-ui.features.icons', true);
    $configIcon = config('rail-ui.loading.icon');
    $rawIcon = ($configIcon && is_string($configIcon)) ? $configIcon : 'loader';
    $resolvedIcon = $c ? $c->resolveIcon() : trim(preg_replace('/^(ti-|tabler-)/i', '', $icon ?? $rawIcon));
    $spinnerLabel = $c ? $c->label : ($label ?? 'Loading');
@endphp

@if($usesTabler)
    <x-dynamic-component
        :component="'tabler-' . $resolvedIcon"
        {{ $attributes->merge([
            'class'      => $spinnerClasses,
            'role'       => 'status',
            'aria-label' => $spinnerLabel,
        ]) }}
    />
@else
    <svg
        {{ $attributes->merge([
            'class'      => $spinnerClasses,
            'role'       => 'status',
            'aria-label' => $spinnerLabel,
        ]) }}
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
    >
        <circle
            class="ui-spinner__track"
            cx="12" cy="12" r="10"
            stroke="currentColor"
            stroke-width="3"
        />
        <path
            class="ui-spinner__head"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
        />
    </svg>
@endif
