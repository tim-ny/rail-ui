<?php

namespace Aegis\Ui\Components;

use Aegis\Ui\Concerns\HasDisabled;
use Aegis\Ui\Concerns\HasUnstyled;

class DropdownItem extends BaseComponent
{
    use HasDisabled, HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'dropdown-item';
    }

    protected static function viewName(): string
    {
        return 'ui::components.dropdown-item';
    }

    public function __construct(
        public ?string $href    = null,
        public ?string $icon    = null,
        public ?string $shortcut = null,
        public bool    $danger  = false,
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
            $this->danger ? 'ui-dropdown__item--danger' : null,
            $this->disabled ? 'ui-dropdown__item--disabled' : null,
        );
    }
}
