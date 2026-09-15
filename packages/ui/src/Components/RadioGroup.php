<?php
/**
 * @component  RadioGroup
 * @type       Blade
 * @tag        <x-radio-group />
 * @props      name, value, label, hint, error, valid, required, disabled, size, color, unstyled, wireModel, wireModelModifier
 * @slots      $slot
 * @decisions  Alpine.js-powered radio group. The group manages selected state via
 *             x-model binding and passes it to child <x-radio> components. Supports
 *             vertical and horizontal layouts, optional label, hint, and error feedback.
 */

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasSize;
use Rail\Ui\Concerns\HasColor;
use Rail\Ui\Concerns\HasDisabled;
use Rail\Ui\Concerns\HasUnstyled;
use Rail\Ui\Concerns\HasValidation;
use Rail\Ui\Concerns\InteractsWithWire;
use Illuminate\Support\Str;

class RadioGroup extends BaseComponent
{
    use HasSize, HasColor, HasDisabled, HasUnstyled, HasValidation, InteractsWithWire;

    protected static function componentConfigKey(): string
    {
        return 'radio-group';
    }

    protected static function viewName(): string
    {
        return 'ui::components.radiogroup';
    }

    public function __construct(
        public ?string  $value    = null,
        public ?string  $label    = null,
        bool            $disabled = false,
        string          $size     = 'md',
        string          $color    = 'primary',
        bool            $unstyled = false,
        ?string         $hint     = null,
        ?string         $error    = null,
        bool            $valid    = false,
        bool            $required = false,
        ?string         $id       = null,
        ?string         $name     = null,
        ?string         $wireModel        = null,
        ?string         $wireModelModifier = null,
    ) {
        $this->size     = $this->resolveDefault('radio-group', 'size', $size, 'md');
        $this->color    = $this->resolveDefault('radio-group', 'color', $color, 'primary');
        $this->disabled = $disabled;
        $this->unstyled = $unstyled;
        $this->hint     = $hint;
        $this->error    = $error;
        $this->valid    = $valid;
        $this->required = $required;
        $this->wireModel         = $wireModel;
        $this->wireModelModifier = $wireModelModifier;
        $this->bootHasValidation();

        if (! $this->id) {
            $this->id = 'ui-radio-group-' . Str::random(8);
        }
        if (! $this->name) {
            $this->name = $this->id;
        }
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-radio-group',
            "ui-radio-group--{$this->size}",
            "ui-radio-group--{$this->color}",
            $this->disabled ? 'ui-radio-group--disabled' : null,
        );
    }
}
