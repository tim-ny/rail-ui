<?php
/**
 * @component  Button
 * @type       Blade
 * @tag        <x-button />
 * @props      variant, size, color, as, type, href, target, external, disabled, loading, leadingIcon, trailingIcon, unstyled, wireModel
 * @slots      $slot, $loadingIndicator
 * @decisions  Polymorphic root element tag. If href is set or as="a", root element resolves to "a".
 *             When href is present with disabled or loading state, aria-disabled="true" is set and href is omitted.
 */

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasSize;
use Rail\Ui\Concerns\HasVariant;
use Rail\Ui\Concerns\HasColor;
use Rail\Ui\Concerns\HasIcon;
use Rail\Ui\Concerns\HasDisabled;
use Rail\Ui\Concerns\HasLoading;
use Rail\Ui\Concerns\HasBlock;
use Rail\Ui\Concerns\HasUnstyled;
use Rail\Ui\Concerns\InteractsWithWire;

class Button extends BaseComponent
{
    use HasSize, HasVariant, HasColor, HasIcon, HasDisabled, HasLoading, HasBlock, HasUnstyled, InteractsWithWire;

    protected static function componentConfigKey(): string
    {
        return 'button';
    }

    protected static function viewName(): string
    {
        return 'ui::components.button';
    }

    protected array $allowedVariants = ['solid', 'outline', 'ghost', 'soft'];

    public function __construct(
        string          $variant      = 'solid',
        string          $size         = 'md',
        string          $color        = 'primary',
        public string   $as           = 'button',
        public string   $type         = 'button',
        public ?string  $href         = null,
        public ?string  $target       = null,
        public bool     $external     = false,
        bool            $disabled     = false,
        bool            $loading      = false,
        bool            $block        = false,
        ?string         $leadingIcon  = null,
        ?string         $trailingIcon = null,
        bool            $unstyled     = false,
        ?string         $wireModel    = null,
    ) {
        $this->size         = $this->resolveDefault('button', 'size', $size, 'md');
        $this->variant      = $this->resolveDefault('button', 'variant', $variant, 'solid');
        $this->color        = $this->resolveDefault('button', 'color', $color, 'primary');
        $this->disabled     = $disabled;
        $this->loading      = $loading;
        $this->block        = $block;
        $this->unstyled     = $unstyled;
        $this->leadingIcon  = $leadingIcon;
        $this->trailingIcon = $trailingIcon;
        $this->wireModel    = $wireModel;

        if ($this->href !== null && $this->as === 'button') {
            $this->as = 'a';
        }
    }

    public function resolveTag(): string
    {
        if ($this->href !== null || $this->as === 'a') {
            return 'a';
        }

        return $this->as;
    }

    public function resolveType(): ?string
    {
        return $this->resolveTag() === 'button' ? $this->type : null;
    }

    public function resolveTarget(): ?string
    {
        if ($this->resolveTag() !== 'a') {
            return null;
        }

        if ($this->external) {
            return '_blank';
        }

        return $this->target;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-btn',
            "ui-btn--{$this->variant}",
            "ui-btn--{$this->size}",
            "ui-btn--{$this->color}",
            $this->block    ? 'ui-btn--block'    : null,
            $this->loading  ? 'ui-btn--loading'  : null,
            $this->disabled ? 'ui-btn--disabled' : null,
        );
    }
}
