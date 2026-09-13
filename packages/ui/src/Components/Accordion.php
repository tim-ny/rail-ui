<?php
/**
 * @component  Accordion
 * @type       Blade
 * @tag        <x-accordion />
 * @props      multiple, variant, size, color, defaultOpen, block, unstyled
 * @slots      $slot
 * @decisions  Alpine.js root container component managing active item state,
 *             supporting both single-open and multi-open accordion modes.
 */

namespace Aegis\Ui\Components;

use Aegis\Ui\Concerns\HasSize;
use Aegis\Ui\Concerns\HasVariant;
use Aegis\Ui\Concerns\HasColor;
use Aegis\Ui\Concerns\HasBlock;
use Aegis\Ui\Concerns\HasUnstyled;

class Accordion extends BaseComponent
{
    use HasSize, HasVariant, HasColor, HasBlock, HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'accordion';
    }

    protected static function viewName(): string
    {
        return 'ui::components.accordion';
    }

    public function __construct(
        public bool     $multiple    = false,
        string          $variant     = 'outline',
        string          $size        = 'md',
        string          $color       = 'neutral',
        public mixed    $defaultOpen = null,
        bool            $block       = false,
        bool            $unstyled    = false,
    ) {
        $this->allowedVariants = ['outline', 'soft', 'ghost', 'flush'];
        $this->allowedSizes = ['sm', 'md', 'lg'];

        $this->size    = $this->resolveDefault('accordion', 'size', $size, 'md');
        $this->variant = $this->resolveDefault('accordion', 'variant', $variant, 'outline');
        $this->color   = $this->resolveDefault('accordion', 'color', $color, 'neutral');
        $this->block   = $block;
        $this->unstyled = $unstyled;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-accordion',
            "ui-accordion--{$this->variant}",
            "ui-accordion--{$this->size}",
            "ui-accordion--{$this->color}",
            $this->block ? 'ui-accordion--block' : null,
        );
    }
}
