<?php
/**
 * @component  Card
 * @type       Blade
 * @tag        <x-card />
 * @props      padding, shadow, border, href, overflow, unstyled
 * @slots      $media, $header, $default, $footer, $avatar
 * @decisions  Flexible layout container. Supports default, horizontal, and profile
 *             layouts via slots. Resolves defaults via config/rail-ui.php.
 */

namespace Rail\Ui\Components;

use Rail\Ui\Concerns\HasUnstyled;

class Card extends BaseComponent
{
    use HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'card';
    }

    protected static function viewName(): string
    {
        return 'ui::components.card';
    }

    public function __construct(
        public string  $padding  = 'md',
        public string  $shadow   = 'sm',
        public bool    $border   = true,
        public ?string $href     = null,
        public bool    $overflow = false,
        bool           $unstyled = false,
    ) {
        $this->unstyled = $unstyled;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        return $this->classNames(
            'ui-card',
            "ui-card--padding-{$this->padding}",
            "ui-card--shadow-{$this->shadow}",
            $this->border   ? 'ui-card--border'   : null,
            $this->overflow ? 'ui-card--overflow' : null,
            $this->href     ? 'ui-card--link'     : null,
        );
    }
}
