{{--
    @component  Accordion
    @tag        <x-accordion />
    @props      (see src/Components/Accordion.php)
--}}
@props([
    'multiple'    => false,
    'variant'     => 'outline',
    'size'        => 'md',
    'color'       => 'neutral',
    'defaultOpen' => null,
    'block'       => false,
    'unstyled'    => false,
])

<div
    x-data="{
        multiple: {{ $multiple ? 'true' : 'false' }},
        active: {{ json_encode($defaultOpen ?? ($multiple ? [] : null)) }},
        toggle(id) {
            if (this.multiple) {
                if (!Array.isArray(this.active)) {
                    this.active = this.active ? [this.active] : [];
                }
                this.active = this.active.includes(id)
                    ? this.active.filter(i => i !== id)
                    : [...this.active, id];
            } else {
                this.active = this.active === id ? null : id;
            }
        },
        isOpen(id) {
            return this.multiple
                ? (Array.isArray(this.active) && this.active.includes(id))
                : this.active === id;
        }
    }"
    {{ $attributes->merge([
        'class' => ($component ?? null) ? $component->classes() : ($unstyled ? '' : 'ui-accordion ui-accordion--outline ui-accordion--md ui-accordion--neutral'),
    ]) }}
>
    {{ $slot }}
</div>
