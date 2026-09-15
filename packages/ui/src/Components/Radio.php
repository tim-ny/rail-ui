<?php
/**
 * @component  Radio
 * @type       Blade
 * @tag        <x-radio />
 * @props      value, label, description, disabled, unstyled
 * @slots      $slot
 * @decisions  Individual radio item. Uses visually hidden native input with a styled
 *             circle + dot driven by :checked pseudo-class. Supports label and optional
 *             description text. Must be used inside <x-radio-group>.
 */

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasDisabled;
use Rail\Ui\Concerns\HasUnstyled;

class Radio extends BaseComponent
{
    use HasDisabled, HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'radio';
    }

    protected static function viewName(): string
    {
        return 'ui::components.radio';
    }

    public function __construct(
        public ?string $value       = null,
        public ?string $label       = null,
        public ?string $description = null,
        bool           $disabled    = false,
        bool           $unstyled    = false,
    ) {
        $this->disabled = $disabled;
        $this->unstyled = $unstyled;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        return $this->classNames(
            'ui-radio',
            $this->disabled ? 'ui-radio--disabled' : null,
        );
    }
}
