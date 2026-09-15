<?php
/**
 * @component  Select
 * @type       Blade
 * @tag        <x-select />
 * @props      options, value, label, placeholder, hint, error, valid, required, disabled,
 *             readonly, multiple, searchable, clearable, size, color, unstyled, wireModel, wireModelModifier
 * @slots      $slot, $option
 * @decisions  Alpine.js-powered custom select with keyboard navigation, search filtering,
 *             single and multiple selection modes. Options support icon, image, description,
 *             and disabled state. Options can be passed as PHP array or via $option slot.
 */

namespace Aegis\Ui\Components;

use Aegis\Ui\Concerns\HasSize;
use Aegis\Ui\Concerns\HasColor;
use Aegis\Ui\Concerns\HasDisabled;
use Aegis\Ui\Concerns\HasUnstyled;
use Aegis\Ui\Concerns\HasValidation;
use Aegis\Ui\Concerns\InteractsWithWire;
use Illuminate\Support\Str;

class Select extends BaseComponent
{
    use HasSize, HasColor, HasDisabled, HasUnstyled, HasValidation, InteractsWithWire;

    protected static function componentConfigKey(): string
    {
        return 'select';
    }

    protected static function viewName(): string
    {
        return 'ui::components.select';
    }

    public function __construct(
        public mixed     $options     = [],
        public mixed     $value       = null,
        public ?string   $label       = null,
        public ?string   $placeholder = 'Select...',
        bool             $disabled    = false,
        bool             $readonly    = false,
        public bool      $multiple    = false,
        public bool      $searchable  = false,
        public bool      $clearable   = false,
        string           $size        = 'md',
        string           $color       = 'primary',
        bool             $unstyled    = false,
        ?string          $hint        = null,
        ?string          $error       = null,
        bool             $valid       = false,
        bool             $required    = false,
        ?string          $id          = null,
        ?string          $name        = null,
        ?string          $wireModel        = null,
        ?string          $wireModelModifier = null,
    ) {
        $this->size     = $this->resolveDefault('select', 'size', $size, 'md');
        $this->color    = $this->resolveDefault('select', 'color', $color, 'primary');
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->unstyled = $unstyled;
        $this->hint     = $hint;
        $this->error    = $error;
        $this->valid    = $valid;
        $this->required = $required;
        $this->wireModel         = $wireModel;
        $this->wireModelModifier = $wireModelModifier;
        $this->bootHasValidation();

        // Set id/name after bootHasValidation (which may set them from label)
        if (! $this->id || str_starts_with($this->id, 'ui-')) {
            $this->id = $this->label ? 'ui-select-' . Str::slug($this->label) : ($this->id ?: 'ui-select-' . Str::random(8));
        }
        if (! $this->name || str_starts_with($this->name, 'ui-')) {
            $this->name = $this->id;
        }

        $this->options = $this->normalizeOptions($this->options);
    }

    /**
     * Normalize options into a consistent format:
     * [['value' => 'x', 'label' => 'X', 'icon' => null, 'image' => null, 'description' => null, 'disabled' => false]]
     */
    private function normalizeOptions(mixed $options): array
    {
        if (! is_array($options)) return [];

        $normalized = [];
        foreach ($options as $key => $option) {
            if (is_string($option)) {
                $normalized[] = [
                    'value'       => $option,
                    'label'       => $option,
                    'icon'        => null,
                    'image'       => null,
                    'description' => null,
                    'disabled'    => false,
                ];
            } elseif (is_array($option)) {
                $normalized[] = [
                    'value'       => $option['value'] ?? $option['label'] ?? $key,
                    'label'       => $option['label'] ?? $option['value'] ?? $key,
                    'icon'        => $option['icon'] ?? null,
                    'image'       => $option['image'] ?? null,
                    'description' => $option['description'] ?? null,
                    'disabled'    => $option['disabled'] ?? false,
                ];
            }
        }
        return $normalized;
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();

        return $this->classNames(
            'ui-select',
            "ui-select--{$this->size}",
            "ui-select--{$this->color}",
            $this->disabled ? 'ui-select--disabled' : null,
            $this->readonly ? 'ui-select--readonly' : null,
        );
    }
}
