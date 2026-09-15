<?php

namespace Rail\Ui\Concerns;

trait HasLoading
{
    public bool $loading = false;

    public function isLoading(): bool
    {
        return $this->loading;
    }

    public function loadingClass(): string
    {
        return $this->loading ? 'ui-loading' : '';
    }
}
