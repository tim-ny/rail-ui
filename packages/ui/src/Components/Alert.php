<?php
/**
 * @component  Alert
 * @type       Blade
 * @tag        <x-alert />
 * @props      variant, size, color, title, icon, dismissible, block, unstyled
 * @slots      $slot, $title, $icon
 * @decisions  Resolves fallback icon automatically per color theme if no icon prop is passed.
 *             Role defaults to "alert" for danger/warning and "status" for primary/success/neutral.
 */

namespace Aegis\Ui\Components;

use Aegis\Ui\Concerns\HasSize;
use Aegis\Ui\Concerns\HasVariant;
use Aegis\Ui\Concerns\HasColor;
use Aegis\Ui\Concerns\HasIcon;
use Aegis\Ui\Concerns\HasBlock;
use Aegis\Ui\Concerns\HasUnstyled;

class Alert extends BaseComponent
{
    use HasSize, HasVariant, HasColor, HasIcon, HasBlock, HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'alert';
    }

    protected static function viewName(): string
    {
        return 'ui::components.alert';
    }

    public function __construct(
        string          $variant     = 'soft',
        string          $size        = 'md',
        string          $color       = 'primary',
        public ?string  $title       = null,
        public ?string  $icon        = null,
        ?string         $leadingIcon = null,
        public bool     $dismissible = false,
        bool            $block       = false,
        bool            $unstyled    = false,
    ) {
        $this->allowedVariants = ['soft', 'outline', 'solid', 'ghost'];
        $this->allowedSizes = ['sm', 'md', 'lg'];

        $this->size        = $this->resolveDefault('alert', 'size', $size, 'md');
        $this->variant     = $this->resolveDefault('alert', 'variant', $variant, 'soft');
        $this->color       = $this->resolveDefault('alert', 'color', $color, 'primary');
        $this->block       = $block;
        $this->unstyled    = $unstyled;
        $this->leadingIcon = $leadingIcon;
    }

    public function resolveIcon(): ?string
    {
        $targetIcon = $this->icon ?? $this->leadingIcon;

        if ($targetIcon === 'none' || $targetIcon === 'false') {
            return null;
        }

        if ($targetIcon) {
            return $targetIcon;
        }

        return match ($this->color) {
            'danger'  => 'alert-circle',
            'warning' => 'alert-triangle',
            'success' => 'circle-check',
            'primary' => 'info-circle',
            default   => 'info-circle',
        };
    }

    public function resolveRole(): string
    {
        return in_array($this->color, ['danger', 'warning'], true) ? 'alert' : 'status';
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-alert',
            "ui-alert--{$this->variant}",
            "ui-alert--{$this->size}",
            "ui-alert--{$this->color}",
            $this->block ? 'ui-alert--block' : null,
        );
    }
}
