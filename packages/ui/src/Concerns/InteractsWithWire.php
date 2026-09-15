<?php

namespace Rail\Ui\Concerns;

trait InteractsWithWire
{
    public ?string $wireModel = null;
    public ?string $wireModelModifier = null; // 'lazy', 'blur', 'live'

    protected array $allowedWireModifiers = ['lazy', 'blur', 'live'];

    public function wireModelAttribute(): ?string
    {
        if (! $this->wireModel) {
            return null;
        }

        if ($this->wireModelModifier) {
            if (! in_array($this->wireModelModifier, $this->allowedWireModifiers, true)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Invalid wire:model modifier "%s". Allowed: %s.',
                        $this->wireModelModifier,
                        implode(', ', $this->allowedWireModifiers)
                    )
                );
            }

            return "wire:model.{$this->wireModelModifier}=\"{$this->wireModel}\"";
        }

        return "wire:model.lazy=\"{$this->wireModel}\""; // Livewire 4 default
    }
}
