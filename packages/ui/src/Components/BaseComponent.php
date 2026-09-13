<?php

namespace Aegis\Ui\Components;

use Illuminate\View\Component;

abstract class BaseComponent extends Component
{
    /**
     * Must return the config key for this component, e.g. 'button'.
     * Used to resolve defaults from config/aegis-ui.php.
     */
    abstract protected static function componentConfigKey(): string;

    /**
     * Must return the Blade view name, e.g. 'ui::components.button'.
     */
    abstract protected static function viewName(): string;

    /**
     * Resolve a prop default via:
     * 1. inline prop (handled by Blade before this runs)
     * 2. published config/aegis-ui.php
     * 3. package fallback
     */
    protected function resolveDefault(string $component, string $prop, mixed $value, mixed $packageDefault = null): mixed
    {
        $packageDefault = $packageDefault ?? $value;

        if (function_exists('config') && function_exists('app') && app()->bound('config')) {
            $configDefault = config("aegis-ui.defaults.{$component}.{$prop}");
            if ($configDefault !== null && $value === $packageDefault) {
                return $configDefault;
            }
        }

        return $value;
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
        if (method_exists($this, 'validateRadius'))  $this->validateRadius();
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

    /**
     * Render the component view. Auto-validates props before rendering.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        $this->validate();

        return view(static::viewName());
    }
}
