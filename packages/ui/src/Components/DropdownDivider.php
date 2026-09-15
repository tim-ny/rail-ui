<?php

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasUnstyled;

class DropdownDivider extends BaseComponent
{
    use HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'dropdown-divider';
    }

    protected static function viewName(): string
    {
        return 'ui::components.dropdown-divider';
    }

    public function __construct(
        bool $unstyled = false,
    ) {
        $this->unstyled = $unstyled;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';
        return 'ui-dropdown__divider';
    }
}
