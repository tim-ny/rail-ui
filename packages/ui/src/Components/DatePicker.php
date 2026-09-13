<?php
/**
 * @component  DatePicker
 * @type       Blade
 * @tag        <x-datepicker />
 * @props      mode, format, size, color, value, label, hint, error, valid, required, readonly, disabled, weekNumbers, presets, disabledDates, min, max, firstDayOfWeek, numberOfMonths, fixedWeeks, clearable, viewControl, monthControls, yearControls, placeholder, id, name, unstyled, wireModel, wireModelModifier
 * @slots      none
 * @decisions  Alpine-driven calendar with no server round-trip. mode selects the selection
 *             granularity + multiplicity: single, range, multiple, month, month-range, year,
 *             year-range. All ranges are ordered lists of ISO cell keys, so range logic is
 *             shared across day / month / year granularities. The display input is readonly;
 *             the real value is emitted to Livewire through a hidden input that dispatches
 *             native input/change events when the selection changes. Disabled dates are an
 *             array of ISO keys plus optional inclusive min/max bounds.
 */

namespace Aegis\Ui\Components;

use Aegis\Ui\Concerns\HasSize;
use Aegis\Ui\Concerns\HasColor;
use Aegis\Ui\Concerns\HasDisabled;
use Aegis\Ui\Concerns\HasUnstyled;
use Aegis\Ui\Concerns\HasValidation;
use Aegis\Ui\Concerns\InteractsWithWire;

class DatePicker extends BaseComponent
{
    use HasSize, HasColor, HasDisabled, HasUnstyled, HasValidation, InteractsWithWire;

    protected static function componentConfigKey(): string
    {
        return 'datepicker';
    }

    protected static function viewName(): string
    {
        return 'ui::components.datepicker';
    }

    protected array $allowedModes = [
        'single', 'range', 'multiple',
        'month', 'month-range',
        'year', 'year-range',
    ];

    protected array $allowedFormats = [
        'M d, Y', 'MM-DD-YYYY', 'DD-MM-YYYY', 'YYYY-MM-DD', 'D d M, Y', 'MMMM d, Y',
    ];

    public function __construct(
        public string      $mode            = 'single',
        public string      $format          = 'M d, Y',
        string             $size            = 'md',
        string             $color           = 'primary',
        public mixed       $value           = null,
        public ?string     $label           = null,
        ?string            $hint            = null,
        ?string            $error           = null,
        bool               $valid           = false,
        bool               $required        = false,
        bool               $readonly        = false,
        bool               $disabled        = false,
        public bool        $weekNumbers     = false,
        public bool|array  $presets         = false,
        public array       $disabledDates   = [],
        public ?string     $min             = null,
        public ?string     $max             = null,
        public int         $firstDayOfWeek  = 0,
        public int         $numberOfMonths  = 1,
        public bool        $fixedWeeks      = false,
        public bool        $clearable       = false,
        public bool        $viewControl     = true,
        public bool        $monthControls   = true,
        public bool        $yearControls    = true,
        public ?string     $placeholder     = null,
        ?string            $id              = null,
        ?string            $name            = null,
        bool               $unstyled        = false,
        ?string            $wireModel       = null,
        ?string            $wireModelModifier = null,
    ) {
        $this->mode              = $this->resolveDefault('datepicker', 'mode', $mode, 'single');
        $this->format            = $this->resolveDefault('datepicker', 'format', $format, 'M d, Y');
        $this->size              = $this->resolveDefault('datepicker', 'size', $size, 'md');
        $this->color             = $this->resolveDefault('datepicker', 'color', $color, 'primary');
        $this->placeholder       = $this->placeholder ?? 'Select date';
        $this->disabled          = $disabled;
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

    public function validateMode(): void
    {
        if (! in_array($this->mode, $this->allowedModes, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '[%s] Invalid mode "%s". Allowed: %s.',
                    class_basename(static::class),
                    $this->mode,
                    implode(', ', $this->allowedModes)
                )
            );
        }
    }

    public function validateFormat(): void
    {
        if (! in_array($this->format, $this->allowedFormats, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '[%s] Invalid format "%s". Allowed: %s.',
                    class_basename(static::class),
                    $this->format,
                    implode(', ', $this->allowedFormats)
                )
            );
        }
    }

    public function classes(): string
    {
        if ($this->unstyled) return '';

        $this->validate();
        $this->validateMode();
        $this->validateFormat();

        return $this->classNames(
            'ui-datepicker',
            "ui-datepicker--{$this->size}",
            "ui-datepicker--{$this->color}",
            "ui-datepicker--{$this->mode}",
            $this->weekNumbers ? 'ui-datepicker--week-numbers' : null,
            $this->disabled ? 'ui-datepicker--disabled' : null,
            $this->readonly ? 'ui-datepicker--readonly' : null,
        );
    }

    public function granularity(): string
    {
        return match ($this->mode) {
            'month', 'month-range'  => 'month',
            'year', 'year-range'    => 'year',
            default                 => 'day',
        };
    }

    public function selectType(): string
    {
        return match ($this->mode) {
            'range', 'month-range', 'year-range' => 'range',
            'multiple'                           => 'multiple',
            default                              => 'single',
        };
    }

    public function initialView(): string
    {
        return match ($this->granularity()) {
            'month' => 'month',
            'year'  => 'year',
            default => 'day',
        };
    }

    public function initialSelected(): mixed
    {
        if (! $this->value) {
            return match ($this->selectType()) {
                'range'    => ['start' => null, 'end' => null],
                'multiple' => [],
                default    => null,
            };
        }

        return match ($this->selectType()) {
            'range' => $this->normalizeRange($this->value),
            'multiple' => $this->normalizeMultiple($this->value),
            default => (string) $this->value,
        };
    }

    public function initialViewMonth(): int
    {
        $key = $this->firstSelectedKey();
        if ($key) {
            $parts = $this->splitKey($key);
            return (int) $parts[1] - 1;
        }
        return (int) now()->format('n') - 1;
    }

    public function initialViewYear(): int
    {
        $key = $this->firstSelectedKey();
        if ($key) {
            $parts = $this->splitKey($key);
            return (int) $parts[0];
        }
        return (int) now()->format('Y');
    }

    public function presetsConfig(): array
    {
        if ($this->presets === false || $this->presets === []) {
            return [];
        }

        if (is_array($this->presets)) {
            return array_values(array_filter($this->presets, fn ($p) => is_array($p)));
        }

        return $this->defaultPresets();
    }

    public function wireValue(): string
    {
        $selected = $this->initialSelected();

        return match ($this->selectType()) {
            'range' => $this->rangeToString($selected),
            'multiple' => is_array($selected) ? implode(',', $selected) : '',
            default => $selected ? (string) $selected : '',
        };
    }

    protected function normalizeRange(mixed $value): array
    {
        if (is_array($value)) {
            $values = array_values($value);
            return ['start' => $values[0] ?? null, 'end' => $values[1] ?? null];
        }

        $parts = preg_split('/\s+(?:-|to)\s+/i', (string) $value);

        return [
            'start' => isset($parts[0]) && $parts[0] !== '' ? trim($parts[0]) : null,
            'end'   => isset($parts[1]) && $parts[1] !== '' ? trim($parts[1]) : null,
        ];
    }

    protected function normalizeMultiple(mixed $value): array
    {
        $parts = is_array($value) ? $value : explode(',', (string) $value);

        return array_values(array_filter(array_map('trim', $parts)));
    }

    protected function firstSelectedKey(): ?string
    {
        $selected = $this->initialSelected();

        if (is_array($selected)) {
            if (isset($selected['start']) && $selected['start']) return $selected['start'];
            if (isset($selected[0]) && $selected[0]) return $selected[0];
            return null;
        }

        return $selected ? (string) $selected : null;
    }

    protected function splitKey(string $key): array
    {
        $parts = explode('-', $key);

        return [
            (int) ($parts[0] ?? 1),
            (int) ($parts[1] ?? 1),
            (int) ($parts[2] ?? 1),
        ];
    }

    protected function rangeToString(mixed $selected): string
    {
        $start = is_array($selected) ? ($selected['start'] ?? null) : null;
        $end   = is_array($selected) ? ($selected['end'] ?? null) : null;

        if ($start && $end) return "{$start} - {$end}";
        if ($start) return $start;

        return '';
    }

    protected function defaultPresets(): array
    {
        $today = now()->startOfDay();

        return [
            ['label' => 'Today',       'value' => $today->toDateString()],
            ['label' => 'Yesterday',   'value' => $today->copy()->subDay()->toDateString()],
            ['label' => 'Last 7 days', 'start' => $today->copy()->subDays(6)->toDateString(), 'end' => $today->toDateString()],
            ['label' => 'Last 14 days','start' => $today->copy()->subDays(13)->toDateString(), 'end' => $today->toDateString()],
            ['label' => 'Last 30 days','start' => $today->copy()->subDays(29)->toDateString(), 'end' => $today->toDateString()],
            ['label' => 'This month',  'start' => $today->copy()->startOfMonth()->toDateString(), 'end' => $today->copy()->endOfMonth()->toDateString()],
            ['label' => 'This year',   'start' => $today->copy()->startOfYear()->toDateString(), 'end' => $today->copy()->endOfYear()->toDateString()],
        ];
    }
}
