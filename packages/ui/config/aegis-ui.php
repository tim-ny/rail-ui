<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Component Prefix
    |--------------------------------------------------------------------------
    | Controls the tag prefix. '' (default) makes components resolve like
    | <x-button>, <x-input>, etc. — exactly as if they lived in the app's own
    | resources/views/components folder. Set to 'ui' for <x-ui-button>.
    */
    'prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Global Component Defaults
    |--------------------------------------------------------------------------
    | These are the fallback values when no prop is passed inline.
    | Publish this config and change these to set app-wide defaults.
    |
    | Resolution order: inline prop > this config > package default
    */
    'defaults' => [
        'accordion' => [
            'variant' => 'outline',
            'size' => 'md',
            'color' => 'neutral',
        ],
        'accordion-item' => [],
        'alert' => [
            'variant' => 'soft',
            'size' => 'md',
            'color' => 'primary',
        ],
        'button' => [
            'variant' => 'solid',
            'size' => 'md',
            'color' => 'primary',
        ],
        'input' => [
            'size' => 'md',
            'variant' => 'outline',
            'color' => 'primary',
        ],
        'checkbox' => [
            'size' => 'md',
            'color' => 'primary',
            'radius' => 'sm',
        ],
        'datepicker' => [
            'mode' => 'single',
            'format' => 'M d, Y',
            'size' => 'md',
            'color' => 'primary',
        ],
        'textarea' => [
            'size' => 'md',
            'variant' => 'outline',
            'color' => 'primary',
        ],
        'spinner' => [
            'size' => 'md',
        ],
        'icon' => [
            'size' => 'md',
        ],
        'form-field' => [],
        // @planned — component not yet built
        'badge' => [
            'size' => 'md',
            'variant' => 'soft',
            'color' => 'primary',
        ],
        // @planned — component not yet built
        'toggle' => [
            'size' => 'md',
            'color' => 'primary',
        ],
        // @planned — component not yet built
        'modal' => [
            'size' => 'md',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Loading Indicators
    |--------------------------------------------------------------------------
    | Global defaults for every loading/spinner state in the package
    | (standalone <x-spinner>, <x-button loading>, <x-input loading>).
    |
    | 'icon'   The Tabler icon rendered while loading. Any icon registered by
    |          blade-tabler-icons works, e.g. 'loader', 'loader-2', 'refresh'.
    |          Accepts optional "tabler-" / "ti-" prefixes. Override inline with
    |          the spinner `icon` prop for one-off cases.
    */
    'loading' => [
        'icon' => 'loader-2',
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    | Disable features you do not need.
    */
    'features' => [
        'livewire' => true,
        'icons' => true,
        'icon_cache' => env('UI_ICON_CACHE', false),
    ],

];
