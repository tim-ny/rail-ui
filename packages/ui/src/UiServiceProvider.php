<?php

namespace Rail\Ui;

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

        // Register Blade-only components. Without a prefix the package behaves
        // as if its components lived in the app's own resources/views/components
        // folder: <x-button>, <x-input>, etc.
        $bladeComponents = [
            'accordion'      => \Rail\Ui\Components\Accordion::class,
            'accordion-item' => \Rail\Ui\Components\AccordionItem::class,
            'alert'          => \Rail\Ui\Components\Alert::class,
            'banner'         => \Rail\Ui\Components\Banner::class,
            'button'         => \Rail\Ui\Components\Button::class,
            'card'           => \Rail\Ui\Components\Card::class,
            'checkbox'       => \Rail\Ui\Components\Checkbox::class,
            'datepicker'     => \Rail\Ui\Components\DatePicker::class,
            'dropdown'       => \Rail\Ui\Components\Dropdown::class,
            'dropdown-item'  => \Rail\Ui\Components\DropdownItem::class,
            'dropdown-header' => \Rail\Ui\Components\DropdownHeader::class,
            'dropdown-divider' => \Rail\Ui\Components\DropdownDivider::class,
            'dropdown-checkbox' => \Rail\Ui\Components\DropdownCheckbox::class,
            'dropdown-submenu' => \Rail\Ui\Components\DropdownSubmenu::class,
            'spinner'        => \Rail\Ui\Components\Spinner::class,
            'icon'           => \Rail\Ui\Components\Icon::class,
            'form-field'     => \Rail\Ui\Components\FormField::class,
            'input'          => \Rail\Ui\Components\Input::class,
            'radio-group'    => \Rail\Ui\Components\RadioGroup::class,
            'radio'          => \Rail\Ui\Components\Radio::class,
            'select'         => \Rail\Ui\Components\Select::class,
            'toggle'         => \Rail\Ui\Components\Toggle::class,
            'textarea'       => \Rail\Ui\Components\Textarea::class,
        ];

        foreach ($bladeComponents as $alias => $class) {
            Blade::component($class, $prefix !== '' ? "{$prefix}-{$alias}" : $alias);
        }

        // Register Livewire components
        if (config('rail-ui.features.livewire', true)) {
            $livewireComponents = [
                // Livewire components registered dynamically as created
            ];

            foreach ($livewireComponents as $alias => $class) {
                Livewire::component($prefix !== '' ? "{$prefix}-{$alias}" : $alias, $class);
            }
        }

        if (config('rail-ui.features.icon_cache', false)) {
            if (class_exists(\BladeUI\Icons\Factory::class)) {
                \BladeUI\Icons\Factory::cache();
            }
        }
    }
}
