<?php

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasDisabled;
use Rail\Ui\Concerns\HasUnstyled;
use Illuminate\Support\Str;

class DropdownCheckbox extends BaseComponent
{
    use HasDisabled, HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'dropdown-checkbox';
    }

    protected static function viewName(): string
    {
        return 'ui::components.dropdown-checkbox';
    }

    public function __construct(
        public ?string $label   = null,
        public ?string $name    = null,
        public bool    $checked = false,
        bool           $disabled = false,
        bool           $unstyled = false,
    ) {
        $this->disabled = $disabled;
        $this->unstyled = $unstyled;

        if (! $this->name) {
            $this->name = 'dd-check-' . Str::random(8);
        }
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        return $this->classNames(
            'ui-dropdown__item',
            'ui-dropdown__item--checkbox',
            $this->disabled ? 'ui-dropdown__item--disabled' : null,
        );
    }
}
