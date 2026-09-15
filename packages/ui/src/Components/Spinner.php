<?php
/**
 * @component  Spinner
 * @type       Blade
 * @tag        <x-spinner />
 * @props      size, color, label, icon
 * @slots      none
 * @decisions  Renders the Tabler "loader-2" icon by default (configurable via
 *             config('rail-ui.loading.icon') or the `icon` prop) so every loading
 *             state in the package shares one visual language. The spin
 *             animation is applied to the icon SVG itself via the ui-spinner
 *             class. Falls back to the built-in SVG when config('rail-ui.features.icons')
 *             is disabled. Inherits the current text color by default.
 */

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasSize;

class Spinner extends BaseComponent
{
    use HasSize;

    protected static function componentConfigKey(): string
    {
        return 'spinner';
    }

    protected static function viewName(): string
    {
        return 'ui::components.spinner';
    }

    protected array $allowedSizes = ['xs', 'sm', 'md', 'lg', 'xl'];

    public function __construct(
        string  $size  = 'md',
        public ?string $color = null,
        public string  $label = 'Loading',
        public ?string $icon  = null,
    ) {
        $this->size = $this->resolveDefault('spinner', 'size', $size, 'md');
    }

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
        $configIcon = config('rail-ui.loading.icon');
        $rawIcon = ($configIcon && is_string($configIcon)) ? $configIcon : 'loader';
        $icon = trim(preg_replace('/^(ti-|tabler-)/i', '', $this->icon ?? $rawIcon));

        if ($icon === '') {
            throw new \InvalidArgumentException('Spinner icon must be a non-empty Tabler icon name.');
        }

        return $icon;
    }
}
