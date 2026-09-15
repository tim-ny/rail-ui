<?php

namespace Rail\Ui\Concerns;

trait HasColor
{
    public string $color = 'primary';

    protected array $allowedColors = ['primary', 'danger', 'success', 'warning', 'neutral'];

    public function bootHasColor(): void
    {
        $this->color = $this->resolveDefault(
            static::componentConfigKey(),
            'color',
            $this->color
        );
    }

    public function validateColor(): void
    {
        if (! in_array($this->color, $this->allowedColors, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '[%s] Invalid color "%s". Allowed: %s.',
                    class_basename(static::class),
                    $this->color,
                    implode(', ', $this->allowedColors)
                )
            );
        }
    }
}
