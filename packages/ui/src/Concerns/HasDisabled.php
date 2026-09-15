<?php

namespace Rail\Ui\Concerns;

trait HasDisabled
{
    public bool $disabled = false;

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function disabledClass(): string
    {
        return $this->disabled ? 'ui-disabled' : '';
    }
}
