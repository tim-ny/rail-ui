<?php

namespace Rail\Ui\Concerns;

use Illuminate\Support\Str;

trait HasValidation
{
    public ?string $error    = null;
    public bool    $valid    = false;
    public ?string $hint     = null;
    public bool    $readonly = false;
    public bool    $required = false;
    public ?string $id       = null;
    public ?string $name     = null;

    public function bootHasValidation(): void
    {
        // Auto-generate id from label if not provided
        if (! $this->id && property_exists($this, 'label') && $this->label) {
            $this->id = 'ui-' . Str::slug($this->label);
        }

        // name falls back to id
        if (! $this->name) {
            $this->name = $this->id;
        }

        // Auto-pull from Laravel $errors bag if available and no explicit error is set
        if (! $this->error && $this->name && isset($errors) && $errors instanceof \Illuminate\Support\MessageBag) {
            $this->error = $errors->first($this->name) ?: null;
        }
    }

    public function hasError(): bool
    {
        return $this->error !== null && $this->error !== '';
    }

    public function isValid(): bool
    {
        return $this->valid && ! $this->hasError();
    }

    public function validationClass(): string
    {
        if ($this->hasError())  return 'ui-field--error';
        if ($this->isValid())   return 'ui-field--valid';
        if ($this->readonly)    return 'ui-field--readonly';
        return '';
    }

    /**
     * Returns the text to display below the field.
     * Error takes precedence over hint.
     */
    public function feedbackText(): ?string
    {
        return $this->error ?? $this->hint ?? null;
    }

    public function feedbackClass(): string
    {
        return $this->hasError() ? 'ui-field__feedback--error' : 'ui-field__feedback--hint';
    }

    /**
     * The aria-describedby value — links the input to its feedback text.
     */
    public function describedById(): ?string
    {
        return $this->feedbackText() ? "{$this->id}-feedback" : null;
    }
}
