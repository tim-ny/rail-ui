<?php
/**
 * @component  AccordionItem
 * @type       Blade
 * @tag        <x-accordion-item />
 * @props      title, id, leadingIcon, disabled, unstyled
 * @slots      $slot, $title, $trigger, $icon
 * @decisions  Renders collapsible item. Uses Str::slug for title-based IDs, Str::random for fallback.
 */

namespace Aegis\Ui\Components;

use Aegis\Ui\Concerns\HasDisabled;
use Aegis\Ui\Concerns\HasUnstyled;
use Illuminate\Support\Str;

class AccordionItem extends BaseComponent
{
    use HasDisabled, HasUnstyled;

    protected static function componentConfigKey(): string
    {
        return 'accordion-item';
    }

    protected static function viewName(): string
    {
        return 'ui::components.accordion-item';
    }

    public function __construct(
        public ?string $title       = null,
        public ?string $id          = null,
        public ?string $leadingIcon = null,
        bool           $disabled    = false,
        bool           $unstyled    = false,
    ) {
        $this->disabled = $disabled;
        $this->unstyled = $unstyled;

        if (! $this->id) {
            $this->id = $this->title ? 'ui-acc-' . Str::slug($this->title) : 'ui-acc-' . Str::random(8);
        }
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        return $this->classNames(
            'ui-accordion__item',
            $this->disabled ? 'ui-accordion__item--disabled' : null,
        );
    }
}
