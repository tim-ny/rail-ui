<?php

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasDisabled;
use Rail\Ui\Concerns\HasUnstyled;

class DropdownSubmenu extends BaseComponent
{
    use HasDisabled, HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'dropdown-submenu';
    }

    protected static function viewName(): string
    {
        return 'ui::components.dropdown-submenu';
    }

    public function __construct(
        public ?string $label   = null,
        public ?string $icon    = null,
        bool           $disabled = false,
        bool           $unstyled = false,
    ) {
        $this->disabled = $disabled;
        $this->unstyled = $unstyled;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        return $this->classNames(
            'ui-dropdown__item',
            'ui-dropdown__item--submenu',
            $this->disabled ? 'ui-dropdown__item--disabled' : null,
        );
    }
}
