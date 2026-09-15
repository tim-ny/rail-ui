<?php
/**
 * @component  Banner
 * @type       Blade
 * @tag        <x-banner />
 * @props      variant, size, color, icon, title, href, closable, showAfter, autoHide, fixed, unstyled
 * @slots      $slot (description), $icon, $action
 * @decisions  Top-of-page announcement bar. Uses Alpine.js for show/hide transitions,
 *             dismiss, and auto-hide. Resolves defaults via config/rail-ui.php.
 */

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasSize;
use Rail\Ui\Concerns\HasVariant;
use Rail\Ui\Concerns\HasColor;
use Rail\Ui\Concerns\HasUnstyled;

class Banner extends BaseComponent
{
    use HasSize, HasVariant, HasColor, HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'banner';
    }

    protected static function viewName(): string
    {
        return 'ui::components.banner';
    }

    public function __construct(
        public ?string $icon     = null,
        public ?string $title    = null,
        public ?string $href     = null,
        public bool    $closable = true,
        public int     $showAfter  = 300,
        public int     $autoHide = 0,
        public bool    $fixed    = true,
        string        $variant  = 'soft',
        string        $size     = 'md',
        string        $color    = 'primary',
        bool          $unstyled = false,
    ) {
        $this->allowedVariants = ['solid', 'outline', 'soft', 'ghost'];

        $this->variant  = $this->resolveDefault('banner', 'variant', $variant, 'soft');
        $this->size     = $this->resolveDefault('banner', 'size', $size, 'md');
        $this->color    = $this->resolveDefault('banner', 'color', $color, 'primary');
        $this->unstyled = $unstyled;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-banner',
            "ui-banner--{$this->variant}",
            "ui-banner--{$this->size}",
            "ui-banner--{$this->color}",
            $this->fixed ? 'ui-banner--fixed' : null,
        );
    }
}
