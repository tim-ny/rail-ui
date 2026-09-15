<?php
/**
 * @component  Toggle
 * @type       Blade
 * @tag        <x-toggle />
 * @props      label, checked, disabled, size, color, unstyled, wireModel, wireModelModifier
 * @slots      $slot
 * @decisions  Visually hidden checkbox driving a styled track + knob via :checked
 *             pseudo-class. Label sits to the right. Supports sizes and 5 color themes.
 */

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasSize;
use Rail\Ui\Concerns\HasColor;
use Rail\Ui\Concerns\HasDisabled;
use Rail\Ui\Concerns\HasUnstyled;
use Rail\Ui\Concerns\HasValidation;
use Rail\Ui\Concerns\InteractsWithWire;
use Illuminate\Support\Str;

class Toggle extends BaseComponent
{
    use HasSize, HasColor, HasDisabled, HasUnstyled, HasValidation, InteractsWithWire;

    protected static function componentConfigKey(): string
    {
        return 'toggle';
    }

    protected static function viewName(): string
    {
        return 'ui::components.toggle';
    }

    public function __construct(
        public bool     $checked    = false,
        public ?string  $label      = null,
        bool            $disabled   = false,
        string          $size       = 'md',
        string          $color      = 'primary',
        bool            $unstyled   = false,
        ?string         $hint       = null,
        ?string         $error      = null,
        bool            $valid      = false,
        bool            $required   = false,
        ?string         $id         = null,
        ?string         $name       = null,
        ?string         $wireModel        = null,
        ?string         $wireModelModifier = null,
    ) {
        $this->size     = $this->resolveDefault('toggle', 'size', $size, 'md');
        $this->color    = $this->resolveDefault('toggle', 'color', $color, 'primary');
        $this->disabled = $disabled;
        $this->unstyled = $unstyled;
        $this->hint     = $hint;
        $this->error    = $error;
        $this->valid    = $valid;
        $this->required = $required;
        $this->wireModel         = $wireModel;
        $this->wireModelModifier = $wireModelModifier;
        $this->bootHasValidation();

        // Set id/name after bootHasValidation (which may set them from label)
        if (! $this->id || str_starts_with($this->id, 'ui-')) {
            $this->id = $this->label ? 'ui-toggle-' . Str::slug($this->label) : ($this->id ?: 'ui-toggle-' . Str::random(8));
        }
        if (! $this->name || str_starts_with($this->name, 'ui-')) {
            $this->name = $this->id;
        }
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-toggle',
            "ui-toggle--{$this->size}",
            "ui-toggle--{$this->color}",
            $this->disabled ? 'ui-toggle--disabled' : null,
        );
    }
}
