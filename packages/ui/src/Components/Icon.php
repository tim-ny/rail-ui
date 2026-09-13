<?php
/**
 * @component  Icon
 * @type       Blade
 * @tag        <x-icon />
 * @props      name, size, color, label
 * @slots      none
 * @decisions  Accepts name with or without "tabler-" prefix. Strips it before
 *             passing to underlying Blade Icons component to prevent double prefix.
 */

namespace Aegis\Ui\Components;

class Icon extends BaseComponent
{
    protected static function componentConfigKey(): string
    {
        return 'icon';
    }

    protected static function viewName(): string
    {
        return 'ui::components.icon';
    }

    public function __construct(
        public string  $name,
        public string  $size  = 'md',
        public ?string $color = null,
        public ?string $label = null,
    ) {
        $this->name = $this->normaliseName($name);
    }

    private function normaliseName(string $name): string
    {
        return preg_replace('/^tabler-/i', '', $name);
    }

    public function sizeClass(): string
    {
        return match($this->size) {
            'xs'  => 'ui-icon--xs',
            'sm'  => 'ui-icon--sm',
            'md'  => 'ui-icon--md',
            'lg'  => 'ui-icon--lg',
            'xl'  => 'ui-icon--xl',
            default => throw new \InvalidArgumentException("Invalid icon size: {$this->size}"),
        };
    }

    public function isDecorative(): bool
    {
        return $this->label === null;
    }
}
