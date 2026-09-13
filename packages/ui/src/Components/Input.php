<?php
/**
 * @component  Input
 * @type       Blade
 * @tag        <x-input />
 * @props      type, variant, size, color, leadingIcon, trailingIcon, label, hint, error, valid, required, readonly, disabled, id, name, placeholder, autocomplete, autofocus, maxlength, unstyled, wireModel, wireModelModifier
 * @slots      none
 * @decisions  Supports variants [outline, soft, subtle, ghost, none]. When type="password",
 *             includes Alpine toggle for eye/eye-off visibility. Resolves defaults via config/aegis-ui.php.
 */

namespace Aegis\Ui\Components;

use Aegis\Ui\Concerns\HasSize;
use Aegis\Ui\Concerns\HasVariant;
use Aegis\Ui\Concerns\HasColor;
use Aegis\Ui\Concerns\HasIcon;
use Aegis\Ui\Concerns\HasDisabled;
use Aegis\Ui\Concerns\HasLoading;
use Aegis\Ui\Concerns\HasBlock;
use Aegis\Ui\Concerns\HasUnstyled;
use Aegis\Ui\Concerns\HasValidation;
use Aegis\Ui\Concerns\InteractsWithWire;

class Input extends BaseComponent
{
    use HasSize, HasVariant, HasColor, HasIcon, HasDisabled, HasLoading, HasBlock, HasUnstyled, HasValidation, InteractsWithWire;

    protected static function componentConfigKey(): string
    {
        return 'input';
    }

    protected static function viewName(): string
    {
        return 'ui::components.input';
    }

    public function __construct(
        public string   $type         = 'text',
        string          $variant      = 'outline',
        string          $size         = 'md',
        string          $color        = 'primary',
        ?string         $leadingIcon  = null,
        ?string         $trailingIcon = null,
        public ?string  $label        = null,
        ?string         $hint         = null,
        ?string         $error        = null,
        bool            $valid        = false,
        bool            $required     = false,
        bool            $readonly     = false,
        bool            $disabled     = false,
        bool            $loading      = false,
        bool            $block        = false,
        ?string         $id           = null,
        ?string         $name         = null,
        public ?string  $placeholder  = null,
        public ?string  $autocomplete = null,
        public bool     $autofocus    = false,
        public ?int     $maxlength    = null,
        bool            $unstyled     = false,
        ?string         $wireModel        = null,
        ?string         $wireModelModifier = null,
    ) {
        $this->allowedVariants = ['outline', 'soft', 'subtle', 'ghost', 'none'];

        $this->size              = $this->resolveDefault('input', 'size', $size, 'md');
        $this->variant           = $this->resolveDefault('input', 'variant', $variant, 'outline');
        $this->color             = $this->resolveDefault('input', 'color', $color, 'primary');
        $this->disabled          = $disabled;
        $this->loading           = $loading;
        $this->block             = $block;
        $this->unstyled          = $unstyled;
        $this->leadingIcon       = $leadingIcon;
        $this->trailingIcon      = $trailingIcon;
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
            'ui-input',
            "ui-input--{$this->variant}",
            "ui-input--{$this->size}",
            "ui-input--{$this->color}",
            $this->block    ? 'ui-input--block'    : null,
            $this->disabled ? 'ui-input--disabled' : null,
            $this->readonly ? 'ui-input--readonly' : null,
            $this->loading  ? 'ui-input--loading'  : null,
        );
    }
}
