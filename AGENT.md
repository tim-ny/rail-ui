# UI Package — Agent Build Instructions

You are building a Laravel 13 + Livewire 4 + Tailwind CSS 4 UI component package intended for distribution via Composer (Packagist). Read every section before writing any file. Do not improvise structure — follow these instructions exactly.

---

## 1. Ground Rules

- PHP 8.3 minimum. Use typed properties, match expressions, named arguments, and PHP 8.x attributes where appropriate.
- Laravel 13, Livewire 4, Alpine.js 3, Tailwind CSS 4.
- No hardcoded color values anywhere in component styles. Every visual value must reference a `--ui-*` CSS custom property.
- No `class` prop on any component. Layout utilities (`w-full`, `mt-4`) are passed via `$attributes` merge. Style decisions belong to the token layer.
- Throw `InvalidArgumentException` on invalid prop values during development. Never fail silently.
- Every component must have a corresponding test in `tests/Unit/` and `tests/Feature/`.
- Write every file completely. Never stub, never use `// ... rest of implementation`. If a file is long, write it fully.

---

## 2. Repository Structure

Create the following structure exactly. Do not add directories not listed here.

```
packages/ui/
├── composer.json
├── config/
│   └── rail-ui.php
├── src/
│   ├── UiServiceProvider.php
│   ├── Components/
│   │   └── (Blade-only component classes)
│   ├── Livewire/
│   │   └── (Livewire 4 component classes)
│   └── Concerns/
│       ├── HasSize.php
│       ├── HasVariant.php
│       ├── HasColor.php
│       ├── HasIcon.php
│       └── InteractsWithWire.php
├── resources/
│   ├── views/
│   │   ├── components/
│   │   │   └── (blade-only component views)
│   │   └── livewire/
│   │       └── (livewire component views)
│   └── css/
│       └── ui.css
└── tests/
    ├── Unit/
    └── Feature/

apps/docs/
└── (standard Laravel 13 app, path-requires packages/ui)

stubs/
└── ui.css.stub

composer.json          (monorepo root)
package.json
phpunit.xml
```

---

## 3. composer.json (packages/ui)

```json
{
    "name": "PACKAGE_VENDOR/ui",
    "description": "Laravel UI component package",
    "type": "library",
    "require": {
        "php": "^8.3",
        "laravel/framework": "^13.0",
        "livewire/livewire": "^4.0"
    },
    "require-dev": {
        "orchestra/testbench": "^10.0",
        "pestphp/pest": "^3.0",
        "pestphp/pest-plugin-livewire": "^3.0"
    },
    "autoload": {
        "psr-4": {
            "PACKAGE_NAMESPACE\\Ui\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "PACKAGE_NAMESPACE\\Ui\\Tests\\": "tests/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "PACKAGE_NAMESPACE\\Ui\\UiServiceProvider"
            ]
        }
    }
}
```

Replace `PACKAGE_VENDOR` and `PACKAGE_NAMESPACE` with the values provided at build time.

---

## 4. UiServiceProvider.php

```php
<?php

namespace PACKAGE_NAMESPACE\Ui;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class UiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/rail-ui.php', 'rail-ui');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ui');

        $this->publishes([
            __DIR__ . '/../config/rail-ui.php' => config_path('rail-ui.php'),
        ], 'ui-config');

        $this->publishes([
            __DIR__ . '/../resources/css/ui.css' => resource_path('css/ui.css'),
        ], 'ui-css');

        $this->publishes([
            __DIR__ . '/../config/rail-ui.php'        => config_path('rail-ui.php'),
            __DIR__ . '/../resources/css/ui.css' => resource_path('css/ui.css'),
        ], 'ui-assets');

        $prefix = config('rail-ui.prefix', '');

        // Register Blade-only components
        // Add each component here as it is built
        $bladeComponents = [
            'button'  => \PACKAGE_NAMESPACE\Ui\Components\Button::class,
            'input'   => \PACKAGE_NAMESPACE\Ui\Components\Input::class,
            'badge'   => \PACKAGE_NAMESPACE\Ui\Components\Badge::class,
            'toggle'  => \PACKAGE_NAMESPACE\Ui\Components\Toggle::class,
        ];

        foreach ($bladeComponents as $alias => $class) {
            Blade::component($class, $prefix !== '' ? "{$prefix}-{$alias}" : $alias);
        }

        // Register Livewire components
        // Add each component here as it is built
        if (config('rail-ui.features.livewire', true)) {
            $livewireComponents = [
                'modal'    => \PACKAGE_NAMESPACE\Ui\Livewire\Modal::class,
                'combobox' => \PACKAGE_NAMESPACE\Ui\Livewire\Combobox::class,
            ];

            foreach ($livewireComponents as $alias => $class) {
                Livewire::component($prefix !== '' ? "{$prefix}-{$alias}" : $alias, $class);
            }
        }
    }
}
```

---

## 5. config/rail-ui.php

```php
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
        'button' => [
            'variant' => 'solid',
            'size'    => 'md',
            'color'   => 'primary',
        ],
        'input' => [
            'size'    => 'md',
            'variant' => 'outline',
        ],
        'checkbox' => [
            'size'   => 'md',
            'color'  => 'primary',
            'radius' => 'sm',
        ],
        'badge' => [
            'size'    => 'md',
            'variant' => 'soft',
            'color'   => 'primary',
        ],
        'toggle' => [
            'size'  => 'md',
            'color' => 'primary',
        ],
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
        'icons'    => true,
    ],

];
```

---

## 6. CSS Architecture (resources/css/ui.css)

This is the single source of truth for all design tokens and component styles. Every component's styles live here, organised into clearly labelled sections. This file grows as components are added.

### Token naming rules
- All tokens are prefixed `--ui-`.
- Tokens are semantic, not visual. Use `--ui-color-primary` not `--ui-color-blue`.
- Every component CSS value references a token. No hardcoded values.

### File format
```css
@import "tailwindcss";

/* ============================================================
   DESIGN TOKENS
   ============================================================ */

@theme {
    /* --- Colour palette --- */
    /* Insert design language colour tokens here */
    /* Example: */
    /* --ui-color-primary:   oklch(...); */
    /* --ui-color-danger:    oklch(...); */
    /* --ui-color-success:   oklch(...); */
    /* --ui-color-warning:   oklch(...); */
    /* --ui-color-neutral:   oklch(...); */

    /* --- Typography --- */
    /* --ui-font-sans: ...; */
    /* --ui-font-size-xs:   ...; */
    /* --ui-font-size-sm:   ...; */
    /* --ui-font-size-base: ...; */
    /* --ui-font-size-lg:   ...; */

    /* --- Spacing --- */
    /* --ui-space-xs:  ...; */
    /* --ui-space-sm:  ...; */
    /* --ui-space-md:  ...; */
    /* --ui-space-lg:  ...; */
    /* --ui-space-xl:  ...; */

    /* --- Radius --- */
    /* --ui-radius-sm: ...; */
    /* --ui-radius-md: ...; */
    /* --ui-radius-lg: ...; */
    /* --ui-radius-full: 9999px; */

    /* --- Transition --- */
    /* --ui-transition-fast:   150ms ease; */
    /* --ui-transition-normal: 200ms ease; */
    /* --ui-transition-slow:   300ms ease; */

    /* --- Shadow --- */
    /* --ui-shadow-sm: ...; */
    /* --ui-shadow-md: ...; */
}

/* ============================================================
   COMPONENT STYLES
   Add a new section for each component as it is built
   ============================================================ */

@layer components {

    /* ── Button ── */
    /* Add button styles here */

    /* ── Input ── */
    /* Add input styles here */

    /* ── Badge ── */
    /* Add badge styles here */

}
```

**When adding a component**, append its styles to the `@layer components` block with a clearly labelled section comment. Never create separate CSS files per component.

---

## 7. Concerns (Shared Traits)

Build all four traits before building any component. Every component that needs size, variant, color, or icon support must use the relevant trait — never duplicate the logic.

### HasSize.php

```php
<?php

namespace PACKAGE_NAMESPACE\Ui\Concerns;

trait HasSize
{
    public string $size = 'md';

    // Override in component class to restrict allowed sizes
    protected array $allowedSizes = ['xs', 'sm', 'md', 'lg', 'xl'];

    public function bootHasSize(): void
    {
        $this->size = $this->resolveDefault(
            static::componentConfigKey(),
            'size',
            $this->size
        );
    }

    public function validateSize(): void
    {
        if (! in_array($this->size, $this->allowedSizes, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '[%s] Invalid size "%s". Allowed: %s.',
                    class_basename(static::class),
                    $this->size,
                    implode(', ', $this->allowedSizes)
                )
            );
        }
    }
}
```

### HasVariant.php

```php
<?php

namespace PACKAGE_NAMESPACE\Ui\Concerns;

trait HasVariant
{
    public string $variant = 'solid';

    protected array $allowedVariants = ['solid', 'outline', 'ghost', 'soft'];

    public function bootHasVariant(): void
    {
        $this->variant = $this->resolveDefault(
            static::componentConfigKey(),
            'variant',
            $this->variant
        );
    }

    public function validateVariant(): void
    {
        if (! in_array($this->variant, $this->allowedVariants, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '[%s] Invalid variant "%s". Allowed: %s.',
                    class_basename(static::class),
                    $this->variant,
                    implode(', ', $this->allowedVariants)
                )
            );
        }
    }
}
```

### HasColor.php

```php
<?php

namespace PACKAGE_NAMESPACE\Ui\Concerns;

trait HasColor
{
    public string $color = 'primary';

    protected array $allowedColors = ['primary', 'danger', 'success', 'warning', 'neutral'];

    public function bootHasColor(): void
    {
        $this->color = $this->resolveDefault(
            static::componentConfigKey(),
            'color',
            $this->color
        );
    }

    public function validateColor(): void
    {
        if (! in_array($this->color, $this->allowedColors, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '[%s] Invalid color "%s". Allowed: %s.',
                    class_basename(static::class),
                    $this->color,
                    implode(', ', $this->allowedColors)
                )
            );
        }
    }
}
```

### HasIcon.php

```php
<?php

namespace PACKAGE_NAMESPACE\Ui\Concerns;

trait HasIcon
{
    public ?string $leadingIcon  = null;
    public ?string $trailingIcon = null;

    public function hasLeadingIcon(): bool
    {
        return $this->leadingIcon !== null;
    }

    public function hasTrailingIcon(): bool
    {
        return $this->trailingIcon !== null;
    }
}
```

### InteractsWithWire.php

```php
<?php

namespace PACKAGE_NAMESPACE\Ui\Concerns;

trait InteractsWithWire
{
    public ?string $wireModel = null;
    public ?string $wireModelModifier = null; // 'lazy', 'blur', 'live'

    protected array $allowedWireModifiers = ['lazy', 'blur', 'live'];

    public function wireModelAttribute(): ?string
    {
        if (! $this->wireModel) {
            return null;
        }

        if ($this->wireModelModifier) {
            if (! in_array($this->wireModelModifier, $this->allowedWireModifiers, true)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Invalid wire:model modifier "%s". Allowed: %s.',
                        $this->wireModelModifier,
                        implode(', ', $this->allowedWireModifiers)
                    )
                );
            }

            return "wire:model.{$this->wireModelModifier}=\"{$this->wireModel}\"";
        }

        return "wire:model.lazy=\"{$this->wireModel}\""; // Livewire 4 default
    }
}
```

---

## 8. Base Component Class

All Blade component classes extend this. Never extend `Illuminate\View\Component` directly.

```php
<?php

namespace PACKAGE_NAMESPACE\Ui\Components;

use Illuminate\View\Component;

abstract class BaseComponent extends Component
{
    /**
     * Must return the config key for this component, e.g. 'button'.
     * Used to resolve defaults from config/rail-ui.php.
     */
    abstract protected static function componentConfigKey(): string;

    /**
     * Resolve a prop default via:
     * 1. inline prop (handled by Blade before this runs)
     * 2. published config/rail-ui.php
     * 3. package fallback
     */
    protected function resolveDefault(string $component, string $prop, mixed $fallback): mixed
    {
        return config("rail-ui.defaults.{$component}.{$prop}", $fallback);
    }

    /**
     * Validate all props. Call in mount() or render().
     * Each Concern adds its own validateX() method.
     */
    protected function validate(): void
    {
        if (method_exists($this, 'validateSize'))    $this->validateSize();
        if (method_exists($this, 'validateVariant')) $this->validateVariant();
        if (method_exists($this, 'validateColor'))   $this->validateColor();
    }

    /**
     * Merge multiple class arrays into a single string.
     * Accepts null values — they are ignored.
     */
    protected function classNames(mixed ...$classes): string
    {
        return collect($classes)
            ->flatten()
            ->filter()
            ->implode(' ');
    }
}
```

---

## 9. Component Build Protocol

Follow this exact sequence for every component. Do not skip steps.

### Step 1 — Read the component spec (provided per component)

The spec defines:
- Component name and tag
- Supported props (variant, size, color, etc.) and their allowed values
- Slot structure
- Livewire or Blade-only
- Design notes

### Step 2 — Write the PHP class

Location: `src/Components/{Name}.php` or `src/Livewire/{Name}.php`

Rules:
- Extend `BaseComponent` (Blade) or `Livewire\Component` (Livewire).
- Use only traits from `Concerns/` for shared behaviour.
- Declare every prop as a typed public property with a default.
- Implement `componentConfigKey()`.
- Call `$this->validate()` in `render()` (Blade) or `mount()` (Livewire).
- Implement a `classes()` method that returns the fully-resolved class string using `$this->classNames()`.
- Never put CSS class strings in the Blade view — always call `$component->classes()`.

```php
// Minimal example skeleton — flesh out fully for each component

<?php

namespace PACKAGE_NAMESPACE\Ui\Components;

use PACKAGE_NAMESPACE\Ui\Concerns\HasSize;
use PACKAGE_NAMESPACE\Ui\Concerns\HasVariant;
use PACKAGE_NAMESPACE\Ui\Concerns\HasColor;

class Button extends BaseComponent
{
    use HasSize, HasVariant, HasColor;

    protected static function componentConfigKey(): string
    {
        return 'button';
    }

    public function __construct(
        public string $size    = 'md',
        public string $variant = 'solid',
        public string $color   = 'primary',
        public string $as      = 'button',
        public bool   $loading  = false,
        public bool   $disabled = false,
        public ?string $leadingIcon  = null,
        public ?string $trailingIcon = null,
    ) {
        // Apply config/rail-ui.php defaults if props were not passed inline
        $this->size    = $this->resolveDefault('button', 'size', $size);
        $this->variant = $this->resolveDefault('button', 'variant', $variant);
        $this->color   = $this->resolveDefault('button', 'color', $color);
    }

    public function classes(): string
    {
        $this->validate();

        return $this->classNames(
            'ui-btn',
            "ui-btn--{$this->variant}",
            "ui-btn--{$this->size}",
            "ui-btn--{$this->color}",
            $this->loading  ? 'ui-btn--loading'  : null,
            $this->disabled ? 'ui-btn--disabled' : null,
        );
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('ui::components.button');
    }
}
```

### Step 3 — Write the Blade view

Location: `resources/views/components/{name}.blade.php`

Rules:
- `@props([])` must declare every prop the view uses directly.
- Use `$attributes->merge(['class' => $component->classes()])` exactly once on the root element.
- Use named slots (`$leading`, `$trailing`, `$icon`, etc.) for structural injection points. Never `$before` or `$after`.
- Include an `:unstyled` prop that, when `true`, passes an empty string to the class merge instead of `$component->classes()`.
- Conditional rendering of icons and loading states goes here, not in the PHP class.
- No inline `style=""` attributes. No hardcoded Tailwind utilities that duplicate what CSS classes already do.

```blade
{{-- Minimal example skeleton --}}
@props([
    'as'       => 'button',
    'loading'  => false,
    'disabled' => false,
    'unstyled' => false,
    'leadingIcon'  => null,
    'trailingIcon' => null,
])

<{{ $as }}
    {{ $attributes->merge([
        'class'    => $unstyled ? '' : $component->classes(),
        'disabled' => $disabled || $loading,
        'aria-busy' => $loading ? 'true' : null,
        'type'     => $as === 'button' ? 'button' : null,
    ]) }}
>
    @if($loading)
        {{-- Loading indicator slot or default spinner --}}
        {{ $loadingIndicator ?? '' }}
    @elseif($leadingIcon)
        <x-icon :name="$leadingIcon" class="ui-btn__icon ui-btn__icon--leading" />
    @endif

    <span class="ui-btn__label">{{ $slot }}</span>

    @if($trailingIcon && !$loading)
        <x-icon :name="$trailingIcon" class="ui-btn__icon ui-btn__icon--trailing" />
    @endif
</{{ $as }}>
```

### Step 4 — Write CSS for the component

Append to the `@layer components` block in `resources/css/ui.css`.

Rules:
- Use BEM-style class names: `.ui-{component}`, `.ui-{component}--{modifier}`.
- All colour, spacing, radius, and font values must reference `--ui-*` tokens.
- No `!important`.
- Group modifiers in the order: variant → size → color → state.
- State classes (`.ui-btn--loading`, `.ui-btn--disabled`) use CSS attribute selectors where possible (`[disabled]`, `[aria-busy="true"]`) in addition to the class.

```css
/* ── Button ── */
.ui-btn {
    display: inline-flex;
    align-items: center;
    gap: var(--ui-space-sm);
    font-weight: 500;
    border-radius: var(--ui-radius-md);
    transition: background-color var(--ui-transition-fast),
                color var(--ui-transition-fast),
                border-color var(--ui-transition-fast);
    cursor: pointer;
    white-space: nowrap;
    border: 1px solid transparent;
}

/* Variants */
.ui-btn--solid   { /* ... */ }
.ui-btn--outline { /* ... */ }
.ui-btn--ghost   { /* ... */ }
.ui-btn--soft    { /* ... */ }

/* Sizes */
.ui-btn--xs  { height: var(--ui-btn-height-xs);  padding-inline: var(--ui-btn-px-xs);  font-size: var(--ui-font-size-xs);  }
.ui-btn--sm  { height: var(--ui-btn-height-sm);  padding-inline: var(--ui-btn-px-sm);  font-size: var(--ui-font-size-sm);  }
.ui-btn--md  { height: var(--ui-btn-height-md);  padding-inline: var(--ui-btn-px-md);  font-size: var(--ui-font-size-sm);  }
.ui-btn--lg  { height: var(--ui-btn-height-lg);  padding-inline: var(--ui-btn-px-lg);  font-size: var(--ui-font-size-base);}
.ui-btn--xl  { height: var(--ui-btn-height-xl);  padding-inline: var(--ui-btn-px-xl);  font-size: var(--ui-font-size-base);}

/* Colors — cross with variants */
.ui-btn--solid.ui-btn--primary   { background-color: var(--ui-color-primary);   color: var(--ui-color-on-primary);   }
.ui-btn--solid.ui-btn--danger    { background-color: var(--ui-color-danger);    color: var(--ui-color-on-danger);    }
.ui-btn--solid.ui-btn--success   { background-color: var(--ui-color-success);   color: var(--ui-color-on-success);   }
.ui-btn--solid.ui-btn--warning   { background-color: var(--ui-color-warning);   color: var(--ui-color-on-warning);   }
.ui-btn--solid.ui-btn--neutral   { background-color: var(--ui-color-neutral);   color: var(--ui-color-on-neutral);   }

/* States */
.ui-btn--disabled,
.ui-btn[disabled] { opacity: 0.5; cursor: not-allowed; pointer-events: none; }

.ui-btn--loading  { cursor: wait; }
```

### Step 5 — Write tests

**Unit test** (`tests/Unit/{Name}Test.php`) — test the PHP class in isolation:
- Valid prop combinations resolve the correct class string.
- Invalid props throw `InvalidArgumentException`.
- Config defaults are respected.

**Feature test** (`tests/Feature/{Name}RenderTest.php`) — test the rendered HTML:
- Default render contains expected class.
- Prop combinations produce expected markup.
- `$attributes` are forwarded correctly.
- `:unstyled` prop strips classes.

Use Pest syntax throughout.

```php
// Unit example skeleton
it('throws on invalid size', function () {
    expect(fn () => new \PACKAGE_NAMESPACE\Ui\Components\Button(size: 'xxl'))
        ->toThrow(\InvalidArgumentException::class);
});

it('resolves solid primary md classes by default', function () {
    $button = new \PACKAGE_NAMESPACE\Ui\Components\Button();
    expect($button->classes())->toContain('ui-btn--solid', 'ui-btn--primary', 'ui-btn--md');
});
```

### Step 6 — Register the component

Add the component to `UiServiceProvider.php` in the appropriate array (`$bladeComponents` or `$livewireComponents`). Add its default config to `config/rail-ui.php` under `defaults`.

### Step 7 — Add a demo page

In `apps/docs/`, create a route and a Blade view that exercises every prop combination of the component. This is the visual integration test.

---

## 10. Design Language Integration

When the design language is provided, apply it exclusively to `ui.css` inside the `@theme {}` block. The token names are fixed (listed below) — only the values change.

### Required token names

You must define all of the following. Do not invent new names.

```css
@theme {
    /* Colours */
    --ui-color-primary:         ;
    --ui-color-primary-hover:   ;
    --ui-color-primary-active:  ;
    --ui-color-on-primary:      ;

    --ui-color-danger:          ;
    --ui-color-danger-hover:    ;
    --ui-color-danger-active:   ;
    --ui-color-on-danger:       ;

    --ui-color-success:         ;
    --ui-color-success-hover:   ;
    --ui-color-success-active:  ;
    --ui-color-on-success:      ;

    --ui-color-warning:         ;
    --ui-color-warning-hover:   ;
    --ui-color-warning-active:  ;
    --ui-color-on-warning:      ;

    --ui-color-neutral:         ;
    --ui-color-neutral-hover:   ;
    --ui-color-neutral-active:  ;
    --ui-color-on-neutral:      ;

    --ui-color-surface:         ;
    --ui-color-surface-raised:  ;
    --ui-color-border:          ;
    --ui-color-border-strong:   ;
    --ui-color-text:            ;
    --ui-color-text-muted:      ;
    --ui-color-text-subtle:     ;

    /* Soft variant tints (10% opacity of the base colour) */
    --ui-color-primary-soft:    ;
    --ui-color-danger-soft:     ;
    --ui-color-success-soft:    ;
    --ui-color-warning-soft:    ;
    --ui-color-neutral-soft:    ;

    /* Typography */
    --ui-font-sans:             ;
    --ui-font-mono:             ;
    --ui-font-size-xs:          ;   /* 0.75rem */
    --ui-font-size-sm:          ;   /* 0.875rem */
    --ui-font-size-base:        ;   /* 1rem */
    --ui-font-size-lg:          ;   /* 1.125rem */
    --ui-font-size-xl:          ;   /* 1.25rem */

    /* Spacing (used for padding/gap in components) */
    --ui-space-xs:              ;
    --ui-space-sm:              ;
    --ui-space-md:              ;
    --ui-space-lg:              ;
    --ui-space-xl:              ;

    /* Radius */
    --ui-radius-sm:             ;
    --ui-radius-md:             ;
    --ui-radius-lg:             ;
    --ui-radius-full:           9999px;

    /* Transitions */
    --ui-transition-fast:       150ms ease;
    --ui-transition-normal:     200ms ease;
    --ui-transition-slow:       300ms ease;

    /* Shadows */
    --ui-shadow-sm:             ;
    --ui-shadow-md:             ;
    --ui-shadow-lg:             ;

    /* Per-component size tokens (define as needed) */
    /* Button heights */
    --ui-btn-height-xs:         1.5rem;
    --ui-btn-height-sm:         1.75rem;
    --ui-btn-height-md:         2.25rem;
    --ui-btn-height-lg:         2.5rem;
    --ui-btn-height-xl:         3rem;

    /* Button horizontal padding */
    --ui-btn-px-xs:             0.5rem;
    --ui-btn-px-sm:             0.75rem;
    --ui-btn-px-md:             1rem;
    --ui-btn-px-lg:             1.25rem;
    --ui-btn-px-xl:             1.5rem;

    /* Input heights (same scale as button) */
    --ui-input-height-xs:       1.5rem;
    --ui-input-height-sm:       1.75rem;
    --ui-input-height-md:       2.25rem;
    --ui-input-height-lg:       2.5rem;
    --ui-input-height-xl:       3rem;
}
```

---

## 11. Prop API Contract

Every component must follow this prop naming convention. Do not rename props.

| Prop            | Type      | Values                                          | Notes                                      |
|-----------------|-----------|-------------------------------------------------|--------------------------------------------|
| `variant`       | `string`  | `solid`, `outline`, `ghost`, `soft`             | Not all components support all variants    |
| `size`          | `string`  | `xs`, `sm`, `md`, `lg`, `xl`                   | Restrict in `$allowedSizes` if needed      |
| `color`         | `string`  | `primary`, `danger`, `success`, `warning`, `neutral` | Always all five unless noted          |
| `as`            | `string`  | any valid HTML tag or component                 | Polymorphic root element                   |
| `disabled`      | `bool`    | `true`, `false`                                 |                                            |
| `loading`       | `bool`    | `true`, `false`                                 | Shows loading state                        |
| `leadingIcon`   | `?string` | icon name string or null                        |                                            |
| `trailingIcon`  | `?string` | icon name string or null                        |                                            |
| `unstyled`      | `bool`    | `true`, `false`                                 | Strips all package classes                 |
| `wireModel`     | `?string` | any string                                      | Livewire-interactive components only       |

For form inputs, additionally:

| Prop          | Type      | Notes                                         |
|---------------|-----------|-----------------------------------------------|
| `label`       | `?string` | Rendered in `<label>` above the input         |
| `hint`        | `?string` | Helper text below the input                   |
| `error`       | `?string` | Error message; applies error state to input   |
| `required`    | `bool`    |                                               |
| `id`          | `?string` | Auto-generated from label if not provided     |

---

## 12. Slot Contract

Use these slot names consistently. Not all components expose all slots.

| Slot name          | Purpose                                          |
|--------------------|--------------------------------------------------|
| `$slot`            | Primary content (always present)                 |
| `$leading`         | Content injected before the primary slot         |
| `$trailing`        | Content injected after the primary slot          |
| `$label`           | Form label override (replaces `label` prop)      |
| `$hint`            | Form hint override (replaces `hint` prop)        |
| `$error`           | Form error override (replaces `error` prop)      |
| `$trigger`         | Trigger element for modal/dropdown/tooltip       |
| `$header`          | Panel/modal header region                        |
| `$footer`          | Panel/modal footer region                        |
| `$empty`           | Empty state for list/table/combobox              |

Named slots take precedence over their prop equivalents.

---

## 13. Livewire Component Rules

Livewire components follow the same contract but with additional rules:

- Keep the PHP class in `src/Livewire/` and the Blade view in `resources/views/livewire/`.
- Do **not** use Livewire 4 single-file format (the `⚡` prefix). That format is for application code, not distributed packages.
- `wire:model.lazy` is the default modifier. Never use `wire:model.defer` (removed in Livewire 4).
- Use `#[On]` attribute syntax for event listeners (Livewire 4 style).
- Use `#[Locked]` on properties that must not be tampered with from the frontend.
- Scoped `<style>` blocks in Livewire views are allowed and encouraged for component-specific styles that are too dynamic for the static CSS file.

---

## 14. What the Agent Must NOT Do

- Do not add any dependency not listed in `composer.json`.
- Do not create a `tailwind.config.js`. Tailwind 4 is CSS-first.
- Do not add a `class` prop to any component.
- Do not hardcode any color, radius, spacing, or font value in component CSS. Always use `--ui-*` tokens.
- Do not use `wire:model.defer` (Livewire 4 removed it).
- Do not use `@livewire()` directive — use `<livewire:ui-modal />` tag syntax.
- Do not create components that are not in the build plan or explicitly requested.
- Do not stub out method bodies. Write every method fully.
- Do not write a single CSS file per component. All CSS lives in `ui.css`.
- Do not use `!important` anywhere.
- Do not add unsolicited abstractions. If the spec says `Button`, build `Button`. Do not build `ButtonGroup` unless asked.

---

## 15. Build Order

Build in this order. Each item depends on the previous being complete and tested.

1. **Scaffolding** — monorepo structure, `composer.json`, `package.json`, `phpunit.xml`, `UiServiceProvider.php`, `config/rail-ui.php`, `BaseComponent.php`, all four Concerns, `ui.css` skeleton.
2. **Design tokens** — populate `@theme {}` with the provided design language. Do not build any component until tokens are complete.
3. **Button** — the simplest component. Validates the full pipeline (class → view → CSS → test).
4. **Badge** — uses `HasVariant`, `HasColor`, `HasSize`. No interactivity.
5. **Input** — introduces form prop contract (`label`, `hint`, `error`, `required`, `id`). Uses `InteractsWithWire`.
6. **Toggle** — extends form contract. Introduces Alpine.js interaction pattern.
7. **Modal** — first Livewire component. Validates the Livewire pipeline.
8. **Combobox** — most complex component. Built last, after all patterns are established.

Further components are added per request, always following the Step 1–7 protocol.

---

## 16. Receiving a Component Spec

When the developer provides a component spec, it will follow this format:

```
Component: [name]
Type: Blade | Livewire
Props:
  - variant: solid | outline | ghost | soft
  - size: sm | md | lg
  - color: primary | danger | success | warning | neutral
  - [any additional props]
Slots:
  - $slot (primary content)
  - [any named slots]
Notes:
  [design and behaviour notes]
Design language:
  [token values or reference to earlier-provided design language]
```

When you receive a spec, execute Steps 1–7 in order. Do not ask for clarification unless a prop or slot is genuinely ambiguous. Make a reasonable decision, implement it, and note the decision in a comment at the top of the component class file.

---

## 17. File Header Convention

Every PHP file must begin with this header block:

```php
<?php
/**
 * @component  [ComponentName]
 * @type       Blade|Livewire
 * @tag        <x-[name] />
 * @props      variant, size, color, [others]
 * @slots      $slot, [named slots]
 * @decisions  [Any non-obvious implementation decisions made during build]
 */
```

Every Blade view must begin with:

```blade
{{--
    @component  [ComponentName]
    @tag        <x-[name] />
    @props      (see src/Components/[Name].php)
--}}
```

---

## 18. Checklist Before Marking a Component Done

- [ ] PHP class written fully, no stubbed methods
- [ ] All props typed and validated
- [ ] `classes()` method covers all variant × size × color combinations
- [ ] `componentConfigKey()` implemented
- [ ] Blade view uses `$attributes->merge()`
- [ ] `:unstyled` prop works
- [ ] CSS appended to `ui.css` under a named section
- [ ] All CSS values use `--ui-*` tokens
- [ ] Component registered in `UiServiceProvider.php`
- [ ] Defaults added to `config/rail-ui.php`
- [ ] Unit test: valid props, invalid props, config defaults
- [ ] Feature test: rendered HTML, attribute forwarding, unstyled mode
- [ ] Demo page added in `apps/docs/`
- [ ] File header present on PHP class and Blade view

---

## 19. Component Tag Naming

All components use a flat, hyphenated tag. The default is no prefix — each component resolves by its plain name, exactly as if it lived in the app's own `resources/views/components` folder. Do not use dot notation, double colons, or nested namespace separators in the tag name.

| Component     | Tag                    | Type     |
|---------------|------------------------|----------|
| Button        | `<x-button />`         | Blade    |
| Badge         | `<x-badge />`          | Blade    |
| Input         | `<x-input />`          | Blade    |
| Textarea      | `<x-textarea />`       | Blade    |
| Select        | `<x-select />`         | Blade    |
| Checkbox      | `<x-checkbox />`       | Blade    |
| DatePicker    | `<x-datepicker />`     | Blade    |
| Radio         | `<x-radio />`          | Blade    |
| Toggle        | `<x-toggle />`         | Blade    |
| Icon          | `<x-icon />`           | Blade    |
| Spinner       | `<x-spinner />`        | Blade    |
| Avatar        | `<x-avatar />`         | Blade    |
| Alert         | `<x-alert />`          | Blade    |
| Modal         | `<x-modal />`          | Livewire |
| Combobox      | `<x-combobox />`       | Livewire |
| Dropdown      | `<x-dropdown />`       | Blade    |
| Tooltip       | `<x-tooltip />`        | Blade    |
| Tabs          | `<x-tabs />`           | Blade    |
| Card          | `<x-card />`           | Blade    |
| FormField     | `<x-form-field />`     | Blade    |

With the default empty prefix, tags resolve as `<x-button />`, `<x-input />`, etc. — the package behaves as if its components were copied into the app's `resources/views/components` folder. If a dev publishes the config and sets `'prefix' => 'app'`, all tags become `<x-app-button />` with zero code changes. The prefix is applied once in `UiServiceProvider`. Never hardcode a prefix inside a component class or view; internal cross-component references in views must use the plain unprefixed tag (e.g. `<x-icon>`, `<x-spinner>`).

---

## 20. Icon System — Tabler Icons via Blade Icons

### Package dependency

Add to `packages/ui/composer.json` under `require`:

```json
"blade-ui-kit/blade-icons": "^1.6",
"secondnetwork/blade-tabler-icons": "^4.0"
```

`secondnetwork/blade-tabler-icons` is the actively maintained Tabler Icons pack. It auto-syncs with upstream Tabler releases via GitHub Actions weekly. It uses `blade-ui-kit/blade-icons` as its foundation, which is the ecosystem standard.

### Icon component — `src/Components/Icon.php`

The `Icon` component is a thin, opinionated wrapper around the raw `<x-tabler-{name} />` Blade component. Its job is to:

1. Normalise icon names (accept `arrow-right`, `tabler-arrow-right`, or `TablerArrowRight` — all resolve to the same icon).
2. Apply consistent sizing via the `size` prop rather than requiring Tailwind utility classes directly.
3. Apply the `--ui-icon-color` token so icons inherit context colour by default.
4. Provide a `label` prop for accessible `aria-label` on decorative vs. meaningful icons.

```php
<?php
/**
 * @component  Icon
 * @type       Blade
 * @tag        <x-icon />
 * @props      name, size, color, label
 * @decisions  Accepts name with or without "tabler-" prefix. Strips it before
 *             passing to the underlying Blade Icons component to avoid double-prefix.
 */

namespace PACKAGE_NAMESPACE\Ui\Components;

class Icon extends BaseComponent
{
    protected static function componentConfigKey(): string
    {
        return 'icon';
    }

    public function __construct(
        public string  $name,
        public string  $size  = 'md',
        public ?string $color = null,
        public ?string $label = null,   // null = decorative (aria-hidden); string = aria-label
    ) {
        $this->name = $this->normaliseName($name);
    }

    private function normaliseName(string $name): string
    {
        // Strip "tabler-" prefix if the dev accidentally included it
        return preg_replace('/^tabler-/i', '', $name);
    }

    public function sizeClass(): string
    {
        return match($this->size) {
            'xs'  => 'ui-icon--xs',
            'sm'  => 'ui-icon--sm',
            'md'  => 'ui-icon--md',
            'lg'  => 'ui-icon--lg',
            'xl'  => 'ui-icon--xl',
            default => throw new \InvalidArgumentException("Invalid icon size: {$this->size}"),
        };
    }

    public function isDecorative(): bool
    {
        return $this->label === null;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('ui::components.icon');
    }
}
```

### Icon view — `resources/views/components/icon.blade.php`

```blade
{{--
    @component  Icon
    @tag        <x-icon name="arrow-right" size="md" />
    @props      name, size, color, label
--}}
@props([
    'name',
    'size'  => 'md',
    'color' => null,
    'label' => null,
])

@php
    $ariaAttrs = $component->isDecorative()
        ? ['aria-hidden' => 'true', 'focusable' => 'false']
        : ['aria-label'  => $label,  'role'       => 'img'];
@endphp

<x-dynamic-component
    :component="'tabler-' . $name"
    {{ $attributes->merge(array_merge($ariaAttrs, [
        'class' => $component->sizeClass() . ($color ? " text-{$color}" : ''),
    ])) }}
/>
```

### Icon CSS tokens

Add to the `@theme {}` block in `ui.css`:

```css
/* Icon sizes */
--ui-icon-size-xs:   0.75rem;   /* 12px */
--ui-icon-size-sm:   1rem;      /* 16px */
--ui-icon-size-md:   1.25rem;   /* 20px */
--ui-icon-size-lg:   1.5rem;    /* 24px */
--ui-icon-size-xl:   2rem;      /* 32px */
```

Add to `@layer components` in `ui.css`:

```css
/* ── Icon ── */
.ui-icon--xs  { width: var(--ui-icon-size-xs);  height: var(--ui-icon-size-xs);  }
.ui-icon--sm  { width: var(--ui-icon-size-sm);  height: var(--ui-icon-size-sm);  }
.ui-icon--md  { width: var(--ui-icon-size-md);  height: var(--ui-icon-size-md);  }
.ui-icon--lg  { width: var(--ui-icon-size-lg);  height: var(--ui-icon-size-lg);  }
.ui-icon--xl  { width: var(--ui-icon-size-xl);  height: var(--ui-icon-size-xl);  }
```

### Using icons inside other components

When a component accepts `leadingIcon` or `trailingIcon`, render them via `<x-icon />`, never via `<x-tabler-{name} />` directly. This ensures size and colour are always applied consistently.

```blade
@if($leadingIcon)
    <x-icon :name="$leadingIcon" size="sm" aria-hidden="true" class="ui-btn__icon" />
@endif
```

### Icon caching

Enable Blade Icons caching in production. Add to `config/rail-ui.php` under `features`:

```php
'icon_cache' => env('UI_ICON_CACHE', false),
```

In `UiServiceProvider::boot()`, after registering components:

```php
if (config('rail-ui.features.icon_cache')) {
    \BladeUI\Icons\Factory::cache();
}
```

Document in the README that host apps should set `UI_ICON_CACHE=true` in their production `.env`.

---

## 21. Loading States

Every component that can trigger a network action — buttons inside forms, Livewire-connected inputs, comboboxes — must implement a complete, accessible loading state. This is not optional.

### The `Spinner` component

Build this before `Button`. It is a dependency of every loading state.

**`src/Components/Spinner.php`**

```php
<?php
/**
 * @component  Spinner
 * @type       Blade
 * @tag        <x-spinner />
 * @props      size, color, label, icon
 * @decisions  Renders the Tabler "loader-2" icon by default so every loading state
 *             shares one visual language. The icon is configurable via
 *             config('rail-ui.loading.icon') (or the `icon` prop) and accepts optional
 *             "tabler-" / "ti-" prefixes. The spin animation is applied to the
 *             icon SVG itself via the ui-spinner class. Falls back to the built-in
 *             SVG when config('rail-ui.features.icons') is disabled. Inherits the
 *             current text colour via currentColor by default.
 */

namespace PACKAGE_NAMESPACE\Ui\Components;

class Spinner extends BaseComponent
{
    protected static function componentConfigKey(): string
    {
        return 'spinner';
    }

    public function __construct(
        public string  $size  = 'md',
        public ?string $color = null,
        public string  $label = 'Loading',
        public ?string $icon  = null,
    ) {}

    public function sizeClass(): string
    {
        return match($this->size) {
            'xs'  => 'ui-spinner--xs',
            'sm'  => 'ui-spinner--sm',
            'md'  => 'ui-spinner--md',
            'lg'  => 'ui-spinner--lg',
            'xl'  => 'ui-spinner--xl',
            default => throw new \InvalidArgumentException("Invalid spinner size: {$this->size}"),
        };
    }

    public function usesTablerIcon(): bool
    {
        return config('rail-ui.features.icons', true);
    }

    public function resolveIcon(): string
    {
        $icon = trim(preg_replace('/^(ti-|tabler-)/i', '', $this->icon ?? config('rail-ui.loading.icon', 'loader')) ?? 'loader');

        if ($icon === '') {
            throw new \InvalidArgumentException('Spinner icon must be a non-empty Tabler icon name.');
        }

        return $icon;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('ui::components.spinner');
    }
}
```

**`resources/views/components/spinner.blade.php`**

```blade
{{--
    @component  Spinner
    @tag        <x-spinner size="md" label="Loading" />
--}}
@props(['size' => 'md', 'color' => null, 'label' => 'Loading', 'icon' => null])

@php
    $spinnerClasses = 'ui-spinner ' . $component->sizeClass() . ($component->color ? " text-{$component->color}" : '');
@endphp

@if($component->usesTablerIcon())
    <x-dynamic-component
        :component="'tabler-' . $component->resolveIcon()"
        {{ $attributes->merge([
            'class'      => $spinnerClasses,
            'role'       => 'status',
            'aria-label' => $component->label,
        ]) }}
    />
@else
    <svg
        {{ $attributes->merge([
            'class'      => $spinnerClasses,
            'role'       => 'status',
            'aria-label' => $component->label,
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
```

**CSS for Spinner** (append to `@layer components` in `ui.css`):

```css
/* ── Spinner ── */
@keyframes ui-spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}

.ui-spinner {
    animation: ui-spin 700ms linear infinite;
    color: currentColor;
    flex-shrink: 0;
}

.ui-spinner__track { opacity: 0.25; }
.ui-spinner__head  { opacity: 1; }

.ui-spinner--xs { width: var(--ui-icon-size-xs); height: var(--ui-icon-size-xs); }
.ui-spinner--sm { width: var(--ui-icon-size-sm); height: var(--ui-icon-size-sm); }
.ui-spinner--md { width: var(--ui-icon-size-md); height: var(--ui-icon-size-md); }
.ui-spinner--lg { width: var(--ui-icon-size-lg); height: var(--ui-icon-size-lg); }
.ui-spinner--xl { width: var(--ui-icon-size-xl); height: var(--ui-icon-size-xl); }

@media (prefers-reduced-motion: reduce) {
    .ui-spinner { animation-duration: 1500ms; }
}
```

### Loading state rules for Button

When `loading="true"`:

1. Replace the `leadingIcon` (if any) with `<x-spinner />` at the same size.
2. Keep the label text visible. Do not hide it — screen readers need it.
3. Set `disabled` attribute on the element.
4. Set `aria-busy="true"` on the element.
5. Apply `.ui-btn--loading` class which sets `cursor: wait` and reduces opacity to 0.7 (not 0.5 — it should look active, not broken).
6. Do not set `pointer-events: none` — the browser's disabled attribute handles that.

```blade
{{-- Inside button.blade.php, inside the root element --}}
@if($loading)
    <x-spinner :size="$size === 'xl' ? 'md' : 'sm'" aria-hidden="true" />
@elseif($leadingIcon)
    <x-icon :name="$leadingIcon" size="sm" aria-hidden="true" />
@endif

<span class="ui-btn__label">{{ $slot }}</span>

@if($trailingIcon && !$loading)
    <x-icon :name="$trailingIcon" size="sm" aria-hidden="true" />
@endif
```

### Loading state rules for Livewire components

For any Livewire component (Modal, Combobox, etc.) that triggers a server action, use Livewire 4's `wire:loading` directives on the view level — do not manage loading state in PHP.

```blade
{{-- Show spinner while any wire action is in flight --}}
<x-spinner size="sm" wire:loading wire:target="save" aria-hidden="true" />

{{-- Disable the trigger button while loading --}}
<x-button wire:loading.attr="disabled" wire:target="save">Save</x-button>

{{-- Dim the result area while loading --}}
<div wire:loading.class="ui-loading-overlay" wire:target="search">
    {{ $slot }}
</div>
```

**CSS for loading overlay** (append to `@layer components` in `ui.css`):

```css
/* ── Loading overlay (Livewire) ── */
.ui-loading-overlay {
    opacity: 0.5;
    pointer-events: none;
    transition: opacity var(--ui-transition-fast);
}
```

Never use `wire:loading` to completely hide content — always dim or disable. Hiding causes layout shift.

### Loading state for form inputs

Inputs do not show a spinner inside the field. Instead, when `wire:model.lazy` is active and a request is in flight, show a spinner in the `trailingIcon` position:

```blade
{{-- Inside input.blade.php --}}
<div class="ui-input__wrapper">
    <input ... />

    @if($trailingIcon)
        <div class="ui-input__trailing">
            <span wire:loading wire:target="{{ $wireModel }}">
                <x-spinner size="xs" aria-hidden="true" />
            </span>
            <span wire:loading.remove wire:target="{{ $wireModel }}">
                <x-icon :name="$trailingIcon" size="sm" aria-hidden="true" />
            </span>
        </div>
    @endif
</div>
```

If no `trailingIcon` is set, no loading indicator appears on the input itself. The surrounding form's submit button carries the loading state.

---

## 22. Form Validation

This section defines validation behaviour for all form input components: `Input`, `Textarea`, `Select`, `Checkbox`, `Radio`, and `Toggle`.

### Validation states

Every form input component must support three states in addition to its default:

| State       | Prop / source                        | Visual changes                                        |
|-------------|--------------------------------------|-------------------------------------------------------|
| `error`     | `error` prop or `$errors` bag        | Red border, red hint text, error icon in trailing     |
| `valid`     | `valid` prop (explicit)              | Green border, green check icon in trailing            |
| `readonly`  | `readonly` prop                      | Muted border, muted background, cursor `default`      |

The `disabled` state is separate from `readonly`. Disabled fields are not submitted. Readonly fields are submitted but not editable. Both must be visually distinct.

### Prop additions for all form inputs

Add these props to `Input`, `Textarea`, `Select`, `Checkbox`, `Radio`, and `Toggle`:

| Prop          | Type      | Default | Notes                                                                 |
|---------------|-----------|---------|-----------------------------------------------------------------------|
| `error`       | `?string` | `null`  | Inline error message. Also accepts Laravel `$errors->first('field')`. |
| `valid`       | `bool`    | `false` | Explicit valid state. Not inferred — must be set by the developer.    |
| `hint`        | `?string` | `null`  | Helper text shown below the field when no error is present.           |
| `readonly`    | `bool`    | `false` | Sets HTML `readonly` and applies muted visual state.                  |
| `required`    | `bool`    | `false` | Adds `required` attribute and renders a visual required indicator.    |
| `id`          | `?string` | `null`  | Auto-generated from `$label` if not provided (via `Str::slug`).       |
| `name`        | `?string` | `null`  | Passed through to the HTML input. Falls back to `$id`.                |
| `autocomplete`| `?string` | `null`  | Passed through. Common values: `off`, `email`, `current-password`.    |

### `HasValidation` concern — `src/Concerns/HasValidation.php`

Add this to the Concerns list in Section 2's directory structure. Build it before any form input component.

```php
<?php
/**
 * Concern: HasValidation
 * Used by all form input components.
 * Handles error, valid, hint, readonly, required, and id resolution.
 */

namespace PACKAGE_NAMESPACE\Ui\Concerns;

use Illuminate\Support\Str;

trait HasValidation
{
    public ?string $error    = null;
    public bool    $valid    = false;
    public ?string $hint     = null;
    public bool    $readonly = false;
    public bool    $required = false;
    public ?string $id       = null;
    public ?string $name     = null;

    public function bootHasValidation(): void
    {
        // Auto-generate id from label if not provided
        if (! $this->id && property_exists($this, 'label') && $this->label) {
            $this->id = 'ui-' . Str::slug($this->label);
        }

        // name falls back to id
        if (! $this->name) {
            $this->name = $this->id;
        }
    }

    public function hasError(): bool
    {
        return $this->error !== null && $this->error !== '';
    }

    public function isValid(): bool
    {
        return $this->valid && ! $this->hasError();
    }

    public function validationClass(): string
    {
        if ($this->hasError())  return 'ui-field--error';
        if ($this->isValid())   return 'ui-field--valid';
        if ($this->readonly)    return 'ui-field--readonly';
        return '';
    }

    /**
     * Returns the text to display below the field.
     * Error takes precedence over hint.
     */
    public function feedbackText(): ?string
    {
        return $this->error ?? $this->hint ?? null;
    }

    public function feedbackClass(): string
    {
        return $this->hasError() ? 'ui-field__feedback--error' : 'ui-field__feedback--hint';
    }

    /**
     * The aria-describedby value — links the input to its feedback text.
     */
    public function describedById(): ?string
    {
        return $this->feedbackText() ? "{$this->id}-feedback" : null;
    }
}
```

### Laravel `$errors` bag integration

All form input components must automatically pick up errors from Laravel's `$errors` MessageBag when a `name` is set and no `error` prop is passed explicitly.

Add this to `bootHasValidation()` after the existing code:

```php
// Auto-pull from Laravel $errors bag if available and no explicit error is set
if (! $this->error && $this->name && isset($errors) && $errors instanceof \Illuminate\Support\MessageBag) {
    $this->error = $errors->first($this->name) ?: null;
}
```

This means `<x-input name="email" />` inside a standard Laravel form automatically shows the validation error for `email` from `$errors` without any additional props. The developer can always override with `:error="$customMessage"`.

### Input view — validation wiring

This is the full structural pattern for every form input view. Adapt for Textarea, Select, Checkbox, etc.

```blade
{{--
    @component  Input
    @tag        <x-input />
--}}
@props([
    'label'        => null,
    'hint'         => null,
    'error'        => null,
    'valid'        => false,
    'required'     => false,
    'readonly'     => false,
    'id'           => null,
    'name'         => null,
    'type'         => 'text',
    'placeholder'  => null,
    'autocomplete' => null,
    'leadingIcon'  => null,
    'trailingIcon' => null,
    'unstyled'     => false,
])

<div class="{{ $unstyled ? '' : 'ui-form-field ' . $component->validationClass() }}">

    {{-- Label --}}
    @if($label ?? false)
        <label for="{{ $component->id }}" class="ui-form-field__label">
            {{ $label }}
            @if($required)
                <span class="ui-form-field__required" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    {{-- Input wrapper (for icon positioning) --}}
    <div class="ui-input__wrapper">

        @if($leadingIcon)
            <div class="ui-input__leading" aria-hidden="true">
                <x-icon :name="$leadingIcon" size="sm" />
            </div>
        @endif

        <input
            {{ $attributes->merge([
                'id'            => $component->id,
                'name'          => $component->name,
                'type'          => $type,
                'placeholder'   => $placeholder,
                'autocomplete'  => $autocomplete,
                'readonly'      => $readonly,
                'required'      => $required,
                'aria-required' => $required ? 'true' : null,
                'aria-invalid'  => $component->hasError() ? 'true' : null,
                'aria-describedby' => $component->describedById(),
                'class'         => $unstyled ? '' : $component->classes(),
            ]) }}
        />

        {{-- Trailing: validation icon takes precedence over trailing icon prop --}}
        <div class="ui-input__trailing">
            @if($component->hasError())
                <x-icon name="alert-circle" size="sm" class="ui-input__trailing-icon--error" aria-hidden="true" />
            @elseif($component->isValid())
                <x-icon name="circle-check" size="sm" class="ui-input__trailing-icon--valid" aria-hidden="true" />
            @elseif($trailingIcon)
                <x-icon :name="$trailingIcon" size="sm" aria-hidden="true" />
            @endif
        </div>

    </div>

    {{-- Feedback (error or hint) --}}
    @if($component->feedbackText())
        <p
            id="{{ $component->id }}-feedback"
            class="ui-form-field__feedback {{ $component->feedbackClass() }}"
            @if($component->hasError()) role="alert" aria-live="polite" @endif
        >
            {{ $component->feedbackText() }}
        </p>
    @endif

</div>
```

### Checkbox and Radio — structural differences

Checkbox and Radio follow the same validation contract but with a different layout. The label appears to the right of the control, not above it. The native input is visually hidden (`position: absolute; opacity: 0`) but stays in the DOM so `:checked` / `:indeterminate` / `:focus-visible` pseudo-classes drive the styled box and SVG checkmark. The `checked` prop renders the HTML `checked` attribute (initial state only); the `indeterminate` prop sets the property via Alpine `x-init`, never the HTML attribute. The two SVGs (`__check` and `__dash`) are absolutely centered inside `.ui-checkbox__box` (`top/left: 50%` + `translate(-50%, -50%)`) so the invisible one never pushes the visible one off-center. Passing a `$slot` (or `block` prop) renders a full-width card label (`ui-checkbox__label--block`) instead of the box + text row; `$component->block` must be set to `true` in the view so `classes()` includes `ui-checkbox--block`.

```blade
<div class="ui-form-field ui-form-field--inline {{ $component->validationClass() }}">

    <div class="ui-checkbox__wrapper {{ $block ? 'ui-checkbox__wrapper--block' : '' }}">
        <input
            type="checkbox" {{-- or "radio" --}}
            class="ui-checkbox__input"
            {{ $attributes->merge([
                'id'            => $component->id,
                'name'          => $component->name,
                'value'         => $value,
                'checked'       => $checked ? 'checked' : null,
                'required'      => $required ? 'required' : null,
                'disabled'      => ($disabled || $readonly) ? 'disabled' : null,
                'aria-required' => $required ? 'true' : null,
                'aria-invalid'  => $component->hasError() ? 'true' : null,
                'aria-describedby' => $component->describedById(),
                'class'         => $unstyled ? '' : $component->classes(),
            ]) }}
            @if($indeterminate)
                x-data="{ uiIndeterminate: true }"
                x-init="$el.indeterminate = uiIndeterminate"
            @endif
        />

        @if($label ?? false)
            <label for="{{ $component->id }}" class="ui-checkbox__label">
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
        @elseif($block)
            <label for="{{ $component->id }}" class="ui-checkbox__label ui-checkbox__label--block">
                {{ $slot }}
            </label>
        @endif
    </div>

    @if($component->feedbackText())
        <p
            id="{{ $component->id }}-feedback"
            class="ui-form-field__feedback {{ $component->feedbackClass() }}"
            @if($component->hasError()) role="alert" aria-live="polite" @endif
        >
            {{ $component->feedbackText() }}
        </p>
    @endif

</div>
```

Checkbox and Radio do not render a leading/trailing icon area — validation icon placement is intentionally skipped because the styled box already signals state. Color modifiers (`ui-checkbox--{color}`) are applied on the hidden input and style the box via the sibling combinator (e.g. `.ui-checkbox--primary:checked ~ .ui-checkbox__label .ui-checkbox__box`).

### `FormField` wrapper component

`FormField` is a layout-only wrapper component with no visual styling of its own. Its job is to group a label, an arbitrary input slot, and feedback text when the dev is composing their own input rather than using a package input component.

```blade
{{-- Usage --}}
<x-form-field label="Custom field" error="This field is required" required>
    <input type="text" class="..." />
</x-form-field>
```

It accepts the same validation props as form inputs (`label`, `hint`, `error`, `valid`, `required`, `id`) and renders the same label/feedback structure without an `<input>` element of its own.

### Validation CSS tokens

Add to `@theme {}` in `ui.css`:

```css
/* Validation colours */
--ui-color-error:             var(--ui-color-danger);
--ui-color-error-soft:        var(--ui-color-danger-soft);
--ui-color-error-border:      var(--ui-color-danger);
--ui-color-valid:             var(--ui-color-success);
--ui-color-valid-soft:        var(--ui-color-success-soft);
--ui-color-valid-border:      var(--ui-color-success);
```

Add to `@layer components` in `ui.css`:

```css
/* ── Form field ── */
.ui-form-field {
    display: flex;
    flex-direction: column;
    gap: var(--ui-space-xs);
}

.ui-form-field--inline {
    gap: var(--ui-space-sm);
}

.ui-form-field__label {
    font-size: var(--ui-font-size-sm);
    font-weight: 500;
    color: var(--ui-color-text);
    display: flex;
    align-items: center;
    gap: var(--ui-space-xs);
}

.ui-form-field__label--inline {
    font-weight: 400;
}

.ui-form-field__required {
    color: var(--ui-color-error);
    font-weight: 600;
    line-height: 1;
}

.ui-form-field__feedback {
    font-size: var(--ui-font-size-xs);
    line-height: 1.4;
}

.ui-form-field__feedback--error { color: var(--ui-color-error); }
.ui-form-field__feedback--hint  { color: var(--ui-color-text-muted); }

/* ── Input wrapper ── */
.ui-input__wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.ui-input__leading,
.ui-input__trailing {
    position: absolute;
    display: flex;
    align-items: center;
    pointer-events: none;
    color: var(--ui-color-text-subtle);
}

.ui-input__leading  { left: var(--ui-space-sm); }
.ui-input__trailing { right: var(--ui-space-sm); }

/* Pad the input to not overlap icons */
.ui-input__wrapper:has(.ui-input__leading) input  { padding-left: calc(var(--ui-space-sm) * 2 + var(--ui-icon-size-sm)); }
.ui-input__wrapper:has(.ui-input__trailing) input { padding-right: calc(var(--ui-space-sm) * 2 + var(--ui-icon-size-sm)); }

/* ── Validation states on inputs ── */
.ui-field--error  input,
.ui-field--error  textarea,
.ui-field--error  select {
    border-color: var(--ui-color-error-border);
    background-color: var(--ui-color-error-soft);
}

.ui-field--error  input:focus,
.ui-field--error  textarea:focus,
.ui-field--error  select:focus {
    outline-color: var(--ui-color-error-border);
}

.ui-field--valid  input,
.ui-field--valid  textarea,
.ui-field--valid  select {
    border-color: var(--ui-color-valid-border);
}

.ui-field--readonly input,
.ui-field--readonly textarea,
.ui-field--readonly select {
    background-color: var(--ui-color-surface);
    color: var(--ui-color-text-muted);
    cursor: default;
    border-color: var(--ui-color-border);
}

/* Validation icon colours */
.ui-input__trailing-icon--error { color: var(--ui-color-error); }
.ui-input__trailing-icon--valid { color: var(--ui-color-valid); }
```

### Validation test requirements

For every form input component, the Feature tests must cover:

- Renders `aria-invalid="true"` when `error` is set.
- Renders `aria-describedby` linking input to feedback `id`.
- Renders `role="alert"` on feedback paragraph when in error state.
- Error message appears in feedback paragraph.
- Hint appears when no error is set.
- Error supersedes hint when both are set.
- `$errors` bag message is picked up automatically by `name` prop.
- `valid` state renders check icon and valid border class.
- `readonly` renders HTML `readonly` attribute and muted class.
- `required` renders `required` attribute, `aria-required="true"`, and the `*` indicator.
- `id` auto-generates from `label` when not set.

---

## 22a. DatePicker component — Alpine-driven calendar

Blade component at `src/Components/DatePicker.php`, view at
`resources/views/components/datepicker.blade.php`. Tag: `<x-datepicker />`.

### Overview

The calendar is 100% Alpine — opening, navigating, and selecting never round-trip to the
server. The display `<input>` is `readonly`; the real value is carried by a hidden input
(rendered only when `wireModel` is set) that dispatches native `input`/`change` events on
every selection change so `wire:model` keeps working. Selection state is a list of ISO cell
keys (`YYYY-MM-DD`, `YYYY-MM`, or `YYYY` depending on granularity), so all range logic is
shared across day / month / year granularities.

### Modes, granularity, and select type

| Mode           | Granularity | Select type | Value produced                       |
|----------------|-------------|-------------|--------------------------------------|
| `single`       | day         | single      | `2026-08-14`                         |
| `range`        | day         | range       | `2026-08-01 - 2026-08-14`            |
| `multiple`     | day         | multiple    | `2026-08-01,2026-08-14`              |
| `month`        | month       | single      | `2026-08`                            |
| `month-range`  | month       | range       | `2026-01 - 2026-08`                  |
| `year`         | year        | single      | `2026`                               |
| `year-range`   | year        | range       | `2020 - 2026`                        |

`granularity()` derives `day` | `month` | `year` from the mode; `selectType()` derives
`single` | `range` | `multiple`. Range/multiple values are normalized from either an array
(`['2026-08-01', '2026-08-14']`) or a delimited string (`-` / `to` for ranges, `,` for
multiples). Invalid `mode`/`format` values throw `InvalidArgumentException` listing the
allowed values.

### Allowed formats

`M d, Y` (default), `MM-DD-YYYY`, `DD-MM-YYYY`, `YYYY-MM-DD`, `D d M, Y`, `MMMM d, Y`.
Tokens: `YYYY`/`MM`/`DD` are zero-padded numeric; `M` short month; `MMMM` full month;
`D` weekday name; `d` unpadded day. Formatting is client-side via `tokenFormat()`.

### Props

| Prop                | Type          | Default     | Notes                                                              |
|---------------------|---------------|-------------|--------------------------------------------------------------------|
| `mode`              | `string`      | `'single'`  | One of the 7 modes above.                                          |
| `format`            | `string`      | `'M d, Y'`  | One of the 6 allowed formats.                                      |
| `size`              | `string`      | `'md'`      | HasSize levels (`xs`…`xl`).                                        |
| `color`             | `string`      | `'primary'` | HasColor levels.                                                   |
| `value`             | `mixed`       | `null`      | ISO key, array, or delimited string. Used for initial view + value.|
| `label`             | `?string`     | `null`      | Renders `<label for>` above the trigger.                           |
| `hint` / `error`    | `?string`     | `null`      | HasValidation feedback; error wins.                                |
| `valid`             | `bool`        | `false`     | HasValidation valid state.                                         |
| `required`          | `bool`        | `false`     | `required` + `aria-required` + `*` indicator.                      |
| `readonly`          | `bool`        | `false`     | Trigger does not open; muted state.                                |
| `disabled`          | `bool`        | `false`     | Trigger input gets `disabled`; block state.                        |
| `weekNumbers`       | `bool`        | `false`     | ISO-8601 week numbers via `isoWeek()` (UTC-based).                 |
| `presets`           | `bool\|array` | `false`    | `true` → server-built defaults; array passes through as-is.        |
| `disabledDates`     | `array`       | `[]`        | ISO keys that cannot be selected.                                  |
| `min` / `max`       | `?string`     | `null`      | Inclusive ISO key bounds, compared as strings.                     |
| `firstDayOfWeek`    | `int`         | `0`         | `0`=Sun … `6`=Sat; reorders weekday header + grid blanks.          |
| `numberOfMonths`    | `int`         | `1`         | Months shown side-by-side in day view.                             |
| `fixedWeeks`        | `bool`        | `false`     | Pads each month grid to a full 6-week block.                       |
| `clearable`         | `bool`        | `false`     | Shows a × clear button in the trigger.                             |
| `viewControl`       | `bool`        | `true`      | Enables heading click to cycle day → month → year views.           |
| `monthControls`     | `bool`        | `true`      | Shows prev/next chevrons in day/month views.                       |
| `yearControls`      | `bool`        | `true`      | Shows prev/next chevrons in year view.                             |
| `placeholder`       | `?string`     | `null`      | Defaults to `'Select date'`.                                       |
| `id` / `name`       | `?string`     | `null`      | HasValidation auto-generation from `label`.                        |
| `unstyled`          | `bool`        | `false`     | Strips `ui-form-field` + `ui-datepicker` classes.                  |
| `wireModel`         | `?string`     | `null`      | Renders the hidden input bound via `wire:model`.                   |
| `wireModelModifier` | `?string`     | `null`      | Appended to `wire:model` (e.g. `defer`).                           |

### Config defaults

```php
// config/rail-ui.php
'datepicker' => [
    'mode'   => 'single',
    'format' => 'M d, Y',
    'size'   => 'md',
    'color'  => 'primary',
],
```

### Structural notes

- Root is `.ui-form-field`; the trigger + popover live inside it. `classes()` adds
  `ui-datepicker--{size|color|mode}` plus `--week-numbers`, `--disabled`, `--readonly`.
- Popover uses `x-show="open"` + `x-cloak` + `x-transition.opacity`. Root closes on
  `@click.outside` and `@keydown.escape.window`. The docs-site demo roots must add these
  same handlers since they rebuild the calendar via `x-html`.
- Month/year views: clicking a month/year in a day-granularity picker navigates down to the
  day view; in month/year granularity it selects. `previous()`/`next()` jump 12 years in
  year view, 1 year in month view, `numberOfMonths` in day view.
- Range select: first click sets start, second click sets end (auto-swapping if reversed),
  then closes. A third click restarts the range. `multiple` toggles keys in/out; all other
  modes close after one click.
- Livewire: hidden input carries `wireValue` (ISO string, `"start - end"`, or comma-joined)
  and `x-effect` re-syncs `$refs.hiddenInput.value` + dispatches `change`/`input`.
- Presets: when `presets=true`, `defaultPresets()` builds Today / Yesterday / Last 7 & 14 &
  30 days / This month / This year. `applyPreset` fills `{start,end}` for ranges, expands a
  start/end range into the date list for `multiple`, else uses `value`.
- Tests: `tests/Unit/DatePickerTest.php` + `tests/Feature/DatePickerRenderTest.php` cover
  mode/format validation, config defaults, allowed values, render classes, `$errors` bag,
  aria attributes, disabled/readonly/clearable, presets, and wire model attributes.

---

## 23. Additional Missing Props

The following props were omitted from the original contract. Add them to Section 11's Prop API Contract table and implement them on the components listed.

### All form inputs

| Prop            | Type      | Default   | Component(s)                        | Notes                                                       |
|-----------------|-----------|-----------|-------------------------------------|-------------------------------------------------------------|
| `readonly`      | `bool`    | `false`   | Input, Textarea, Select             | HTML `readonly`. Focusable, submitted, not editable.        |
| `autocomplete`  | `?string` | `null`    | Input, Textarea                     | Passed through to HTML. Not validated by the package.       |
| `autofocus`     | `bool`    | `false`   | Input, Textarea, Select             | Passed through. Use sparingly — only one per page.          |
| `name`          | `?string` | `null`    | All form inputs                     | Falls back to `$id`. Required for `$errors` bag resolution. |
| `maxlength`     | `?int`    | `null`    | Input, Textarea                     | Renders HTML `maxlength`. Textarea also shows character count when set. |
| `rows`          | `int`     | `4`       | Textarea only                       | HTML `rows` attribute.                                      |
| `multiple`      | `bool`    | `false`   | Select only                         | HTML `multiple`. Changes height to show several options.    |

### Toggle / Checkbox / Radio

| Prop         | Type      | Default   | Notes                                                                      |
|--------------|-----------|-----------|----------------------------------------------------------------------------|
| `checked`    | `bool`    | `false`   | Initial checked state. For Livewire, use `wire:model` instead.             |
| `value`      | `?string` | `null`    | HTML `value` attribute. Required for radio groups.                         |
| `indeterminate` | `bool` | `false`   | Checkbox only. Sets `indeterminate` via Alpine (`x-init`), not HTML attr.  |
| `radius`     | `string`  | `'sm'`    | Checkbox only. Levels: `none`, `xs`, `sm`, `md`, `lg`, `full`.            |
| `block`      | `bool`    | `false`   | Checkbox only. Renders a full-width card label. Auto-enabled when a `$slot` is passed. |

### Button

| Prop      | Type      | Default    | Notes                                                                  |
|-----------|-----------|------------|------------------------------------------------------------------------|
| `type`    | `string`  | `'button'` | `button`, `submit`, `reset`. Defaults to `button` to prevent accidental form submission. |
| `href`    | `?string` | `null`     | When set, `as` is automatically overridden to `'a'`.                  |
| `target`  | `?string` | `null`     | Only rendered when `href` is set.                                      |
| `external`| `bool`    | `false`    | When `true` with `href`, adds `target="_blank" rel="noopener noreferrer"`. |

### Modal

| Prop         | Type      | Default   | Notes                                                                   |
|--------------|-----------|-----------|-------------------------------------------------------------------------|
| `persistent` | `bool`    | `false`   | When `true`, clicking the backdrop or pressing Escape does not close.   |
| `closeable`  | `bool`    | `true`    | When `false`, hides the close button entirely.                          |
| `maxWidth`   | `string`  | `'md'`    | Maps to `size` token: `sm`, `md`, `lg`, `xl`, `full`.                 |

### Combobox

| Prop          | Type       | Default   | Notes                                                                  |
|---------------|------------|-----------|------------------------------------------------------------------------|
| `multiple`    | `bool`     | `false`   | Allows selecting multiple options.                                     |
| `searchable`  | `bool`     | `true`    | Shows search input inside the dropdown.                                |
| `clearable`   | `bool`     | `false`   | Shows a clear (×) button when a value is selected.                    |
| `placeholder` | `?string`  | `null`    | Shown when no value is selected.                                       |
| `emptyText`   | `string`   | `'No results'` | Shown in `$empty` slot when search returns nothing.               |
| `loading`     | `bool`     | `false`   | Shows spinner in dropdown. For async search via Livewire.              |

### Textarea character count

When `maxlength` is set on Textarea, render a live character count below the field, to the right of the feedback text. Use Alpine for the counter — no Livewire round-trip.

```blade
@if($maxlength)
    <p
        class="ui-form-field__charcount"
        x-data="{ count: $el.previousElementSibling.querySelector('textarea').value.length }"
        x-text="count + ' / {{ $maxlength }}'"
        @input.window="count = $event.target.closest('.ui-form-field')?.querySelector('textarea')?.value?.length ?? count"
        aria-live="polite"
        aria-atomic="true"
    ></p>
@endif
```

Add to `@layer components`:

```css
.ui-form-field__charcount {
    font-size: var(--ui-font-size-xs);
    color: var(--ui-color-text-subtle);
    text-align: right;
    margin-top: calc(var(--ui-space-xs) * -1);
}
```

---

## 24. Updated Build Order

Replace Section 15's build order with this expanded version.

1. **Scaffolding** — full monorepo, all config, `BaseComponent`, all Concerns including `HasValidation`, `ui.css` skeleton with all token names.
2. **Design tokens** — populate `@theme {}` with provided design language. Stop here until tokens are approved.
3. **Spinner** — no dependencies. Required by Button and all Livewire loading states.
4. **Icon** — depends on `secondnetwork/blade-tabler-icons`. Required by Input, Button, Alert.
5. **Button** — depends on Spinner, Icon. Validates the full Blade pipeline.
6. **Badge** — no new dependencies.
7. **Alert** — depends on Icon. Validates icon-inside-component pattern.
8. **FormField** — layout wrapper only. No input element. Validates the label/feedback structure before building real inputs.
9. **Input** — depends on Icon, Spinner, FormField, HasValidation. The reference form input.
10. **Textarea** — mirrors Input with `rows`, `maxlength`, character count.
11. **Select** — mirrors Input without leading/trailing icons.
12. **Checkbox** — inline label layout. Introduces `indeterminate` via Alpine, `radius` levels, and block/card mode via slot.
13. **Radio** — mirrors Checkbox. Validates radio group pattern.
14. **Toggle** — depends on Checkbox pattern and Alpine interaction.
15. **Avatar** — no form dependencies.
16. **Tooltip** — Alpine-only. No server interaction.
17. **Dropdown** — Alpine-only. Validates trigger/panel slot pattern.
18. **Tabs** — Alpine-only. Validates multi-panel slot pattern.
19. **Card** — layout only. Built late because it composes other components in the demo.
20. **Modal** — first Livewire component. Depends on Button, Icon, Spinner.
21. **Combobox** — most complex. Built last.

---

## 25. Updated Checklist

Replace Section 18's checklist with this one. Every item is required before a component is marked done.

**PHP class**
- [ ] Extends `BaseComponent` (Blade) or `Livewire\Component` (Livewire)
- [ ] All props declared as typed public properties with defaults
- [ ] All relevant Concerns used (`HasSize`, `HasVariant`, `HasColor`, `HasValidation`, `HasIcon`, `InteractsWithWire`)
- [ ] `componentConfigKey()` returns the correct key
- [ ] `classes()` covers all variant × size × color combinations, returns no empty strings
- [ ] Invalid prop values throw `InvalidArgumentException` with a descriptive message
- [ ] `bootHasValidation()` auto-generates `id` and `name` where applicable
- [ ] File header block present

**Blade view**
- [ ] `@props([])` declares every prop the view references directly
- [ ] Root element uses `$attributes->merge(['class' => ...])`
- [ ] `:unstyled` prop passes `''` to class merge when true
- [ ] All `aria-*` attributes are present and correct
- [ ] `role="alert"` and `aria-live="polite"` on error feedback
- [ ] `aria-invalid` set from `$component->hasError()`
- [ ] `aria-describedby` links input to its feedback paragraph
- [ ] `aria-hidden="true"` on all decorative icons
- [ ] `aria-label` or `aria-labelledby` on all interactive Livewire components
- [ ] Loading state: Spinner replaces leading icon, `aria-busy` set, label preserved
- [ ] No hardcoded Tailwind utility classes that duplicate CSS class logic
- [ ] Named slots used at documented injection points only
- [ ] File header block present

**CSS**
- [ ] New section appended to `ui.css` under a named comment
- [ ] Every value references a `--ui-*` token — zero hardcoded values
- [ ] States covered: default, hover, focus, active, disabled, error, valid, readonly, loading
- [ ] Focus style uses `outline` with `outline-offset`, not `box-shadow`
- [ ] `@media (prefers-reduced-motion: reduce)` applied to any animation
- [ ] No `!important`

**Registration**
- [ ] Added to `UiServiceProvider` in the correct array
- [ ] Default props added to `config/rail-ui.php` under `defaults`
- [ ] Icon dependency registered if component uses icons

**Tests**
- [ ] Unit: all valid prop combinations
- [ ] Unit: all invalid props throw with descriptive message
- [ ] Unit: config defaults are applied
- [ ] Feature: rendered output contains correct classes
- [ ] Feature: `$attributes` forwarded to root element
- [ ] Feature: `:unstyled` strips all component classes
- [ ] Feature (form inputs): `aria-invalid` present on error state
- [ ] Feature (form inputs): feedback paragraph has correct `id` and `role`
- [ ] Feature (form inputs): `$errors` bag picked up by `name`
- [ ] Feature (form inputs): `valid` state renders check icon class
- [ ] Feature (Livewire): `wire:loading` targets correct action

**Demo**
- [ ] Demo page in `apps/docs/` exercises every prop and slot combination
- [ ] Demo shows error, valid, readonly, and disabled states side by side
- [ ] Demo shows loading state with a button that triggers a fake delay

---

## Installation & Publishing

Consumers install the package with Composer. All package defaults live in
`config/rail-ui.php` and can be overridden per-application after publishing.

**1. Install**

```bash
composer require rail/ui
```

**2. Publish the config**

```bash
php artisan vendor:publish --tag=ui-config
```

This copies `config/rail-ui.php` to the app's `config/` directory. Every default
(component prefixes, per-component defaults, the loading icon, feature flags)
is editable from there — inline component props always win, then the published
config, then the package fallback.

**3. Publish the stylesheet (optional)**

```bash
php artisan vendor:publish --tag=ui-css
```

Copies `resources/css/ui.css` to `resource_path('css/ui.css')` so the app can
own and adjust the design tokens. Publish everything at once with:

```bash
php artisan vendor:publish --provider="Rail\Ui\UiServiceProvider"
```

or `--tag=ui-assets`.

**Publish tags**

| Tag         | Publishes                                          |
|-------------|----------------------------------------------------|
| `ui-config` | `config/rail-ui.php` → `config_path('rail-ui.php')`          |
| `ui-css`    | `resources/css/ui.css` → `resource_path('css/ui.css')` |
| `ui-assets` | Both of the above                                  |

`UiServiceProvider::boot()` must register all three groups (see section 4).
The `mergeConfigFrom()` call in `register()` means published config values
override package defaults without touching `vendor/`.

