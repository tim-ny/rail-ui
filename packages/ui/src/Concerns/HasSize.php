<?php

namespace Rail\Ui\Concerns;

trait HasSize
{
    public string $size = 'md';

    // Override in component class to restrict allowed sizes
    protected array $allowedSizes = ['xs', 'sm', 'md', 'lg', 'xl'];

    public function bootHasSize(): void
    {
        $this->size = $this->resolveDefault(
            static::componentConfigKey(),
            'size',
            $this->size
        );
    }

    public function validateSize(): void
    {
        if (! in_array($this->size, $this->allowedSizes, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '[%s] Invalid size "%s". Allowed: %s.',
                    class_basename(static::class),
                    $this->size,
                    implode(', ', $this->allowedSizes)
                )
            );
        }
    }
}
