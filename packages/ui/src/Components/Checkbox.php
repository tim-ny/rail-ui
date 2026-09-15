<?php
/**
 * @component  Checkbox
 * @type       Blade
 * @tag        <x-checkbox />
 * @props      label, hint, error, valid, required, readonly, disabled, checked, value, indeterminate, size, color, radius, block, id, name, unstyled, wireModel, wireModelModifier
 * @slots      $slot
 * @decisions  Inline label layout — the label sits to the right of the control, not above it.
 *             Uses a visually hidden native input with a styled box + SVG checkmark driven by
 *             the :checked / :indeterminate pseudo-classes, so state updates need no re-render.
 *             Indeterminate is applied via Alpine (x-init), not the HTML attribute.
 *             radius levels: none, xs, sm, md, lg, full (default sm). When the $slot is
 *             non-empty (or block="true"), the label renders as a full-width selectable card
 *             wrapping arbitrary content, checked styling via the same :checked pseudo-class.
 */

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasSize;
use Rail\Ui\Concerns\HasColor;
use Rail\Ui\Concerns\HasDisabled;
use Rail\Ui\Concerns\HasBlock;
use Rail\Ui\Concerns\HasUnstyled;
use Rail\Ui\Concerns\HasValidation;
use Rail\Ui\Concerns\InteractsWithWire;

class Checkbox extends BaseComponent
{
    use HasSize, HasColor, HasDisabled, HasBlock, HasUnstyled, HasValidation, InteractsWithWire;

    protected static function componentConfigKey(): string
    {
        return 'checkbox';
    }

    protected static function viewName(): string
    {
        return 'ui::components.checkbox';
    }

    protected array $allowedRadii = ['none', 'xs', 'sm', 'md', 'lg', 'full'];

    public function __construct(
        public bool     $checked          = false,
        public ?string  $value            = null,
        public bool     $indeterminate    = false,
        bool            $disabled         = false,
        bool            $block            = false,
        bool            $unstyled         = false,
        string   $size             = 'md',
        string   $color            = 'primary',
        public string   $radius           = 'sm',
        public ?string  $label            = null,
        ?string  $hint             = null,
        ?string  $error            = null,
        bool     $valid            = false,
        bool     $readonly         = false,
        bool     $required         = false,
        ?string  $id               = null,
        ?string  $name             = null,
        ?string  $wireModel        = null,
        ?string  $wireModelModifier = null,
    ) {
        $this->size              = $this->resolveDefault('checkbox', 'size', $size, 'md');
        $this->color             = $this->resolveDefault('checkbox', 'color', $color, 'primary');
        $this->radius            = $this->resolveDefault('checkbox', 'radius', $radius, 'sm');
        $this->disabled          = $disabled;
        $this->block             = $block;
        $this->unstyled          = $unstyled;
        $this->hint              = $hint;
        $this->error             = $error;
        $this->valid             = $valid;
        $this->readonly          = $readonly;
        $this->required          = $required;
        $this->id                = $id;
        $this->name              = $name;
        $this->wireModel         = $wireModel;
        $this->wireModelModifier = $wireModelModifier;
        $this->bootHasValidation();
    }

    public function validateRadius(): void
    {
        if (! in_array($this->radius, $this->allowedRadii, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '[%s] Invalid radius "%s". Allowed: %s.',
                    class_basename(static::class),
                    $this->radius,
                    implode(', ', $this->allowedRadii)
                )
            );
        }
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-checkbox',
            "ui-checkbox--{$this->size}",
            "ui-checkbox--{$this->color}",
            "ui-checkbox--radius-{$this->radius}",
            $this->block   ? 'ui-checkbox--block'   : null,
            $this->disabled ? 'ui-checkbox--disabled' : null,
            $this->readonly ? 'ui-checkbox--readonly' : null,
        );
    }
}
