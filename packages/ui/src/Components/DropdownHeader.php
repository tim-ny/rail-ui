<?php

namespace Aegis\Ui\Components;

use Aegis\Ui\Concerns\HasUnstyled;

class DropdownHeader extends BaseComponent
{
    use HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'dropdown-header';
    }

    protected static function viewName(): string
    {
        return 'ui::components.dropdown-header';
    }

    public function __construct(
        bool $unstyled = false,
    ) {
        $this->unstyled = $unstyled;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';
        return 'ui-dropdown__header';
    }
}
