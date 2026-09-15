<?php

namespace Rail\Ui\Concerns;

trait HasBlock
{
    public bool $block = false;

    public function isBlock(): bool
    {
        return $this->block;
    }

    public function blockClass(): string
    {
        return $this->block ? 'ui-block' : '';
    }
}
