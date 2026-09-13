<?php

namespace Aegis\Ui\Concerns;

trait HasUnstyled
{
    public bool $unstyled = false;

    public function isUnstyled(): bool
    {
        return $this->unstyled;
    }
}
