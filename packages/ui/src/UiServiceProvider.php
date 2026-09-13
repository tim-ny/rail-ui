<?php

namespace Aegis\Ui;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class UiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/aegis-ui.php', 'aegis-ui');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ui');

        $this->publishes([
            __DIR__ . '/../config/aegis-ui.php' => config_path('aegis-ui.php'),
        ], 'ui-config');

        $this->publishes([
            __DIR__ . '/../resources/css/ui.css' => resource_path('css/ui.css'),
        ], 'ui-css');

        $this->publishes([
            __DIR__ . '/../config/aegis-ui.php'        => config_path('aegis-ui.php'),
            __DIR__ . '/../resources/css/ui.css' => resource_path('css/ui.css'),
        ], 'ui-assets');

        $prefix = config('aegis-ui.prefix', '');

        // Register Blade-only components. Without a prefix the package behaves
        // as if its components lived in the app's own resources/views/components
        // folder: <x-button>, <x-input>, etc.
        $bladeComponents = [
            'accordion'      => \Aegis\Ui\Components\Accordion::class,
            'accordion-item' => \Aegis\Ui\Components\AccordionItem::class,
            'alert'          => \Aegis\Ui\Components\Alert::class,
            'button'         => \Aegis\Ui\Components\Button::class,
            'checkbox'       => \Aegis\Ui\Components\Checkbox::class,
            'datepicker'     => \Aegis\Ui\Components\DatePicker::class,
            'spinner'        => \Aegis\Ui\Components\Spinner::class,
            'icon'           => \Aegis\Ui\Components\Icon::class,
            'form-field'     => \Aegis\Ui\Components\FormField::class,
            'input'          => \Aegis\Ui\Components\Input::class,
            'textarea'       => \Aegis\Ui\Components\Textarea::class,
        ];

        foreach ($bladeComponents as $alias => $class) {
            Blade::component($class, $prefix !== '' ? "{$prefix}-{$alias}" : $alias);
        }

        // Register Livewire components
        if (config('aegis-ui.features.livewire', true)) {
            $livewireComponents = [
                // Livewire components registered dynamically as created
            ];

            foreach ($livewireComponents as $alias => $class) {
                Livewire::component($prefix !== '' ? "{$prefix}-{$alias}" : $alias, $class);
            }
        }

        if (config('aegis-ui.features.icon_cache', false)) {
            if (class_exists(\BladeUI\Icons\Factory::class)) {
                \BladeUI\Icons\Factory::cache();
            }
        }
    }
}
