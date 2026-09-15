<?php

namespace Rail\Ui\Concerns;

trait HasUnstyled
{
    public bool $unstyled = false;

    public function isUnstyled(): bool
    {
        return $this->unstyled;
    }
}
