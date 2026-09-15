<?php

namespace Rail\Ui\Concerns;

trait HasIcon
{
    public ?string $leadingIcon  = null;
    public ?string $trailingIcon = null;

    public function hasLeadingIcon(): bool
    {
        return $this->leadingIcon !== null;
    }

    public function hasTrailingIcon(): bool
    {
        return $this->trailingIcon !== null;
    }
}
