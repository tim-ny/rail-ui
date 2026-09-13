{{--
    @component  Card
    @tag        <x-card />
    @props      (see src/Components/Card.php)
    @slots      $media, $header, $default, $footer, $avatar
--}}
@props([
    'padding'  => 'md',
    'shadow'   => 'sm',
    'border'   => true,
    'href'     => null,
    'overflow' => false,
    'unstyled' => false,
])

@php
    $c = $component ?? null;
    $cardPadding  = $c ? $c->padding : $padding;
    $cardShadow   = $c ? $c->shadow : $shadow;
    $cardBorder   = $c ? $c->border : $border;
    $cardOverflow = $c ? $c->overflow : $overflow;
    $cardHref     = $c ? $c->href : $href;
    $cardClasses  = $unstyled ? '' : ($c ? $c->classes() : 'ui-card ui-card--padding-' . $cardPadding . ' ui-card--shadow-' . $cardShadow . ($cardBorder ? ' ui-card--border' : '') . ($cardOverflow ? ' ui-card--overflow' : '') . ($cardHref ? ' ui-card--link' : ''));
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $cardClasses }}" {{ $attributes }}>
@else
    <div class="{{ $cardClasses }}" {{ $attributes }}>
@endif

    {{-- Media slot (image/video at top) --}}
    @if(isset($media) && $media)
        <div class="ui-card__media">
            {{ $media }}
        </div>
    @endif

    {{-- Avatar slot (overlapping profile card) --}}
    @if(isset($avatar) && $avatar)
        <div class="ui-card__avatar">
            {{ $avatar }}
        </div>
    @endif

    {{-- Body --}}
    <div class="ui-card__body">
        {{-- Header slot --}}
        @if(isset($header) && $header)
            <div class="ui-card__header">
                {{ $header }}
            </div>
        @endif

        {{-- Default content --}}
        @if($slot)
            <div class="ui-card__content">
                {{ $slot }}
            </div>
        @endif

        {{-- Footer slot --}}
        @if(isset($footer) && $footer)
            <div class="ui-card__footer">
                {{ $footer }}
            </div>
        @endif
    </div>

@if($href)
    </a>
@else
    </div>
@endif
