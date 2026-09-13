<?php
/**
 * @component  Textarea
 * @type       Blade
 * @tag        <x-textarea />
 * @props      rows, maxlength, autoResize, variant, size, color, label, hint, error, valid, required, readonly, disabled, id, name, placeholder, autocomplete, autofocus, unstyled, wireModel, wireModelModifier
 * @slots      none
 * @decisions  Mirrors Input without icons. Adds rows (default 3), maxlength (optional), autoResize for dynamic
 *             height, and a live character count via Alpine.js when maxlength is set.
 */

namespace Aegis\Ui\Components;

use Aegis\Ui\Concerns\HasSize;
use Aegis\Ui\Concerns\HasVariant;
use Aegis\Ui\Concerns\HasColor;
use Aegis\Ui\Concerns\HasDisabled;
use Aegis\Ui\Concerns\HasLoading;
use Aegis\Ui\Concerns\HasBlock;
use Aegis\Ui\Concerns\HasUnstyled;
use Aegis\Ui\Concerns\HasValidation;
use Aegis\Ui\Concerns\InteractsWithWire;

class Textarea extends BaseComponent
{
    use HasSize, HasVariant, HasColor, HasDisabled, HasLoading, HasBlock, HasUnstyled, HasValidation, InteractsWithWire;

    protected static function componentConfigKey(): string
    {
        return 'textarea';
    }

    protected static function viewName(): string
    {
        return 'ui::components.textarea';
    }

    public function __construct(
        public string  $rows              = '3',
        public ?int   $maxlength         = null,
        public bool   $autoResize        = false,
        string        $variant           = 'outline',
        string        $size              = 'md',
        string        $color             = 'primary',
        public ?string $label            = null,
        ?string       $hint              = null,
        ?string       $error             = null,
        bool          $valid             = false,
        bool          $required          = false,
        bool          $readonly          = false,
        bool          $disabled          = false,
        bool          $loading           = false,
        bool          $block             = false,
        ?string       $id                = null,
        ?string       $name              = null,
        public ?string $placeholder      = null,
        public ?string $autocomplete     = null,
        public bool   $autofocus         = false,
        bool          $unstyled          = false,
        ?string       $wireModel         = null,
        ?string       $wireModelModifier = null,
    ) {
        $this->allowedVariants = ['outline', 'soft', 'subtle', 'ghost', 'none'];

        $this->size              = $this->resolveDefault('textarea', 'size', $size, 'md');
        $this->variant           = $this->resolveDefault('textarea', 'variant', $variant, 'outline');
        $this->color             = $this->resolveDefault('textarea', 'color', $color, 'primary');
        $this->disabled          = $disabled;
        $this->loading           = $loading;
        $this->block             = $block;
        $this->unstyled          = $unstyled;
        $this->hint              = $hint;
        $this->error             = $error;
        $this->valid             = $valid;
        $this->required          = $required;
        $this->readonly          = $readonly;
        $this->id                = $id;
        $this->name              = $name;
        $this->wireModel         = $wireModel;
        $this->wireModelModifier = $wireModelModifier;
        $this->bootHasValidation();
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-textarea',
            "ui-textarea--{$this->variant}",
            "ui-textarea--{$this->size}",
            "ui-textarea--{$this->color}",
            $this->block    ? 'ui-textarea--block'    : null,
            $this->disabled ? 'ui-textarea--disabled' : null,
            $this->readonly ? 'ui-textarea--readonly' : null,
            $this->loading  ? 'ui-textarea--loading'  : null,
        );
    }
}
