<?php

namespace Rail\Ui\Concerns;

trait HasVariant
{
    public string $variant = 'solid';

    protected array $allowedVariants = ['solid', 'outline', 'ghost', 'soft'];

    public function bootHasVariant(): void
    {
        $this->variant = $this->resolveDefault(
            static::componentConfigKey(),
            'variant',
            $this->variant
        );
    }

    public function validateVariant(): void
    {
        if (! in_array($this->variant, $this->allowedVariants, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '[%s] Invalid variant "%s". Allowed: %s.',
                    class_basename(static::class),
                    $this->variant,
                    implode(', ', $this->allowedVariants)
                )
            );
        }
    }
}
