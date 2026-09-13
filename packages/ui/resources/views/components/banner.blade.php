{{--
    @component  Banner
    @tag        <x-banner />
    @props      (see src/Components/Banner.php)
--}}
@props([
    'icon'       => null,
    'title'      => null,
    'href'       => null,
    'closable'   => true,
    'showAfter'  => 300,
    'autoHide'   => 0,
    'fixed'      => true,
    'variant'    => 'soft',
    'size'       => 'md',
    'color'      => 'primary',
    'unstyled'   => false,
])

@php
    $c = $component ?? null;
    $bannerIcon     = $c ? $c->icon : $icon;
    $bannerTitle    = $c ? $c->title : $title;
    $bannerHref     = $c ? $c->href : $href;
    $bannerClosable = $c ? $c->closable : $closable;
    $bannerShowAfter = $c ? $c->showAfter : (int) $showAfter;
    $bannerAutoHide = $c ? $c->autoHide : (int) $autoHide;
    $bannerFixed    = $c ? $c->fixed : $fixed;
    $bannerClasses  = $unstyled ? '' : ($c ? $c->classes() : 'ui-banner ui-banner--soft ui-banner--md ui-banner--primary' . ($bannerFixed ? ' ui-banner--fixed' : ''));
@endphp

<div
    x-data="{
        visible: false,
        init() {
            setTimeout(() => { this.visible = true }, {{ $bannerShowAfter }})
            @if($bannerAutoHide > 0)
                setTimeout(() => { this.visible = false }, {{ $bannerShowAfter + $bannerAutoHide }})
            @endif
        }
    }"
    x-show="visible"
    x-transition:enter="transition duration-500 ease-linear"
    x-transition:enter-start="-translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition duration-500 ease-linear"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="-translate-y-full"
    x-cloak
    class="{{ $bannerClasses }}"
    role="banner"
    @if($bannerAutoHide > 0) aria-live="polite" @endif
>
    <div class="ui-banner__inner">
        {{-- Content (icon + title + description) --}}
        @if($bannerHref)
            <a href="{{ $bannerHref }}" class="ui-banner__content">
        @else
            <div class="ui-banner__content">
        @endif

            @if($bannerIcon)
                <span class="ui-banner__icon" aria-hidden="true">
                    <x-icon :name="$bannerIcon" size="sm" />
                </span>
            @endif

            @if($bannerTitle || $slot)
                <span class="ui-banner__text">
                    @if($bannerTitle)
                        <strong class="ui-banner__title">{{ $bannerTitle }}</strong>
                    @endif
                    @if($slot)
                        <span class="ui-banner__description">{{ $slot }}</span>
                    @endif
                </span>
            @endif

            @if(isset($action) && $action)
                <span class="ui-banner__action">
                    {{ $action }}
                </span>
            @endif

        @if($bannerHref)
            </a>
        @else
            </div>
        @endif

        {{-- Close button --}}
        @if($bannerClosable)
            <button
                type="button"
                @click="visible = false"
                class="ui-banner__close"
                aria-label="Dismiss banner"
            >
                <x-icon name="x" size="sm" />
            </button>
        @endif
    </div>
</div>
