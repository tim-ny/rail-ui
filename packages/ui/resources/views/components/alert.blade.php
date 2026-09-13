{{--
    @component  Alert
    @tag        <x-alert />
    @props      (see src/Components/Alert.php)
--}}
@props([
    'variant'     => 'soft',
    'size'        => 'md',
    'color'       => 'primary',
    'title'       => null,
    'icon'        => null,
    'leadingIcon' => null,
    'dismissible' => false,
    'block'       => false,
    'unstyled'    => false,
])

@php
    $c = $component ?? null;
    $resolvedIcon = $c ? $c->resolveIcon() : ($icon ?? $leadingIcon ?? 'info-circle');
    $alertRole = $c ? $c->resolveRole() : (in_array($color, ['danger', 'warning'], true) ? 'alert' : 'status');
    $alertClasses = $unstyled ? '' : ($c ? $c->classes() : "ui-alert ui-alert--{$variant} ui-alert--{$size} ui-alert--{$color}");
@endphp

<div
    @if($dismissible) x-data="{ show: true }" x-show="show" x-transition.opacity @endif
    {{ $attributes->merge([
        'class' => $alertClasses,
        'role'  => $alertRole,
    ]) }}
>
    @if($resolvedIcon)
        <div class="ui-alert__icon" aria-hidden="true">
            <x-icon :name="$resolvedIcon" size="md" />
        </div>
    @endif

    <div class="ui-alert__body">
        @if(isset($titleSlot) || $title)
            <h5 class="ui-alert__title">{{ $titleSlot ?? $title }}</h5>
        @endif
        @if($slot->isNotEmpty())
            <div class="ui-alert__message">{{ $slot }}</div>
        @endif
    </div>

    @if($dismissible)
        <button
            type="button"
            class="ui-alert__dismiss"
            @click="show = false; $dispatch('alert-dismissed')"
            aria-label="Dismiss alert"
        >
            <x-icon name="x" size="sm" aria-hidden="true" />
        </button>
    @endif
</div>
