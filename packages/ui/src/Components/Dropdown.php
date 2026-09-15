<?php
/**
 * @component  Dropdown
 * @type       Blade
 * @tag        <x-dropdown />
 * @props      trigger, align, size, variant, color, unstyled
 * @slots      $slot, $trigger
 * @decisions  Alpine.js-powered contextual menu with items, headers, dividers,
 *             checkboxes, and nested submenus. Popover positioned via CSS.
 */

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasSize;
use Rail\Ui\Concerns\HasVariant;
use Rail\Ui\Concerns\HasColor;
use Rail\Ui\Concerns\HasUnstyled;

class Dropdown extends BaseComponent
{
    use HasSize, HasVariant, HasColor, HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'dropdown';
    }

    protected static function viewName(): string
    {
        return 'ui::components.dropdown';
    }

    public function __construct(
        public ?string $trigger  = null,
        public string  $align    = 'left',
        string         $size     = 'md',
        string         $variant  = 'solid',
        string         $color    = 'primary',
        bool           $unstyled = false,
    ) {
        $this->allowedVariants = ['solid', 'soft', 'outline', 'ghost'];
        $this->allowedSizes    = ['sm', 'md', 'lg'];
        $this->allowedColors   = ['primary', 'neutral', 'danger', 'success', 'warning'];

        $this->size     = $this->resolveDefault('dropdown', 'size', $size, 'md');
        $this->variant  = $this->resolveDefault('dropdown', 'variant', $variant, 'solid');
        $this->color    = $this->resolveDefault('dropdown', 'color', $color, 'primary');
        $this->unstyled = $unstyled;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-dropdown',
            "ui-dropdown--{$this->align}",
            "ui-dropdown--{$this->size}",
            "ui-dropdown--{$this->variant}",
            "ui-dropdown--{$this->color}",
        );
    }
}
