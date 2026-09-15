{{--
    @component  Select
    @tag        <x-select />
    @props      (see src/Components/Select.php)
--}}
@props([
    'options'     => [],
    'value'       => null,
    'label'       => null,
    'placeholder' => 'Select...',
    'disabled'    => false,
    'readonly'    => false,
    'multiple'    => false,
    'searchable'  => false,
    'clearable'   => false,
    'size'        => 'md',
    'color'       => 'primary',
    'unstyled'    => false,
    'hint'        => null,
    'error'       => null,
    'valid'       => false,
    'required'    => false,
    'wireModel'   => null,
])

@php
    $c = $component ?? null;
    $selectId = $c ? $c->id : 'ui-select-' . ($label ? \Illuminate\Support\Str::slug($label) : uniqid());
    $selectName = $c ? $c->name : $selectId;
    $isMultiple = $c ? $c->multiple : $multiple;
    $isSearchable = $c ? $c->searchable : $searchable;
    $isClearable = $c ? $c->clearable : $clearable;
    $opts = $c ? $c->options : (new \Rail\Ui\Components\Select(options: $options))->options;
    $hasErr = $c ? $c->hasError() : !empty($error);
    $isValid = $c ? $c->isValid() : ($valid && !$hasErr);
    $validationCls = $c ? $c->validationClass() : ($hasErr ? 'ui-field--error' : ($isValid ? 'ui-field--valid' : ''));
    $feedbackTxt = $c ? $c->feedbackText() : ($error ?? $hint ?? null);
    $feedbackCls = $c ? $c->feedbackClass() : ($hasErr ? 'ui-form-field__feedback--error' : 'ui-form-field__feedback--hint');
    $selectClasses = $c ? $c->classes() : 'ui-select ui-select--' . ($size ?? 'md') . ' ui-select--' . ($color ?? 'primary') . ($disabled ? ' ui-select--disabled' : '');
    $initialValue = $isMultiple
        ? \Illuminate\Support\Arr::wrap($value ?: [])
        : ($value ?? null);
    $hiddenValueExpr = $isMultiple ? 'selected.join(",")' : '(selected || "")';
@endphp

<div class="{{ $unstyled ? '' : $validationCls }}">
    @if($label)
        <label for="{{ $selectId }}" class="{{ $unstyled ? '' : 'ui-select__label' }}">
            {{ $label }}
            @if($required)
                <span class="ui-form-field__required" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div
        x-data="{
            open: false,
            search: '',
            selected: @js($initialValue),
            activeIndex: -1,
            options: @js($opts),
            get filteredOptions() {
                if (!this.search) return this.options;
                const q = this.search.toLowerCase();
                return this.options.filter(o => o.label.toLowerCase().includes(q) || (o.description && o.description.toLowerCase().includes(q)));
            },
            get displayValue() {
                if (this.multiple) {
                    if (!this.selected.length) return '';
                    return this.selected.length <= 2
                        ? this.options.filter(o => this.selected.includes(o.value)).map(o => o.label).join(', ')
                        : this.selected.length + ' selected';
                }
                const opt = this.options.find(o => o.value === this.selected);
                return opt ? opt.label : '';
            },
            selectOption(opt) {
                if (opt.disabled) return;
                if (this.multiple) {
                    this.selected = this.selected.includes(opt.value)
                        ? this.selected.filter(v => v !== opt.value)
                        : [...this.selected, opt.value];
                } else {
                    this.selected = opt.value;
                    this.open = false;
                }
                this.activeIndex = -1;
                this.search = '';
            },
            isSelected(val) {
                return this.multiple ? this.selected.includes(val) : this.selected === val;
            },
            clearSelection(e) {
                e.stopPropagation();
                this.selected = this.multiple ? [] : null;
            },
            moveActive(dir) {
                const opts = this.filteredOptions;
                if (!opts.length) return;
                this.activeIndex = this.activeIndex + dir;
                if (this.activeIndex < 0) this.activeIndex = opts.length - 1;
                if (this.activeIndex >= opts.length) this.activeIndex = 0;
                this.$nextTick(() => {
                    const el = document.getElementById('ui-select-opt-' + this.activeIndex);
                    if (el) el.scrollIntoView({ block: 'nearest' });
                });
            },
            selectActive() {
                const opts = this.filteredOptions;
                if (this.activeIndex >= 0 && this.activeIndex < opts.length) {
                    this.selectOption(opts[this.activeIndex]);
                }
            }
        }"
        {{ $attributes->merge(['class' => $unstyled ? '' : 'ui-select__wrapper']) }}
        @keydown.escape="open = false"
        @keydown.down.prevent="if(!open) { open = true; return; } moveActive(1)"
        @keydown.up.prevent="if(!open) { open = true; return; } moveActive(-1)"
        @keydown.enter.prevent="if(open) selectActive()"
        @keydown.tab="open = false"
    >
        <input type="hidden" name="{{ $selectName }}" :value="{{ $hiddenValueExpr }}" @if($wireModel)
            @if($c){!! $c->wireModelAttribute() !!}@else wire:model.lazy="{{ $wireModel }}"@endif
        @endif />

        {{-- Trigger --}}
        <button
            type="button"
            x-ref="trigger"
            @click="if(!{{ $disabled ? 'true' : 'false' }}) open = !open"
            :aria-expanded="open ? 'true' : 'false'"
            aria-haspopup="listbox"
            id="{{ $selectId }}"
            @if($disabled) disabled @endif
            class="{{ $selectClasses }}"
        >
            <span x-show="!search" class="ui-select__value" x-text="displayValue || '{{ e($placeholder) }}'"></span>
            @if($isSearchable)
                <input
                    type="text"
                    x-show="open"
                    x-model="search"
                    x-ref="searchInput"
                    class="ui-select__search"
                    placeholder="Search..."
                    @click.stop
                />
            @endif

            @if($isClearable)
                <button
                    type="button"
                    x-show="{{ $isMultiple ? 'selected.length' : 'selected' }}"
                    @click.stop="clearSelection($event)"
                    class="ui-select__clear"
                    aria-label="Clear selection"
                >
                    <x-icon name="x" size="sm" aria-hidden="true" />
                </button>
            @endif

            <x-icon name="chevron-down" size="sm" class="ui-select__chevron" ::class="{ 'ui-select__chevron--open': open }" aria-hidden="true" />
        </button>

        {{-- Dropdown --}}
        <ul
            x-show="open"
            x-cloak
            x-transition:enter="ui-select__dropdown--enter"
            x-transition:enter-start="ui-select__dropdown--enter-start"
            x-transition:enter-end="ui-select__dropdown--enter-end"
            x-transition:leave="ui-select__dropdown--leave"
            x-transition:leave-start="ui-select__dropdown--leave-start"
            x-transition:leave-end="ui-select__dropdown--leave-end"
            @click.away="open = false"
            class="ui-select__dropdown"
            role="listbox"
            :aria-multiselectable="{{ $isMultiple ? 'true' : 'false' }}"
            x-ref="listbox"
        >
            <template x-if="filteredOptions.length === 0">
                <li class="ui-select__empty">No results found</li>
            </template>
            <template x-for="(opt, i) in filteredOptions" :key="opt.value">
                <li
                    :id="'ui-select-opt-' + i"
                    @click="selectOption(opt)"
                    @mousemove="activeIndex = i"
                    :class="{
                        'ui-select__option--active': activeIndex === i,
                        'ui-select__option--selected': isSelected(opt.value),
                        'ui-select__option--disabled': opt.disabled
                    }"
                    class="ui-select__option"
                    role="option"
                    :aria-selected="isSelected(opt.value) ? 'true' : 'false'"
                    :data-disabled="opt.disabled || null"
                >
                    @if($slot->isEmpty())
                        <span class="ui-select__option-media" x-show="opt.icon || opt.image">
                            <template x-if="opt.image">
                                <img :src="opt.image" :alt="opt.label" class="ui-select__option-image" />
                            </template>
                            <template x-if="opt.icon && !opt.image">
                                <i :class="'ti ti-' + opt.icon" class="ui-select__option-icon"></i>
                            </template>
                        </span>
                        <span class="ui-select__option-text">
                            <span class="ui-select__option-label" x-text="opt.label"></span>
                            <span x-show="opt.description" class="ui-select__option-desc" x-text="opt.description"></span>
                        </span>
                        <span x-show="{{ $isMultiple ? 'isSelected(opt.value)' : 'false' }}" class="ui-select__option-check">
                            <x-icon name="check" size="sm" aria-hidden="true" />
                        </span>
                        <span x-show="{{ $isMultiple ? 'false' : 'isSelected(opt.value)' }}" class="ui-select__option-check">
                            <x-icon name="check" size="sm" aria-hidden="true" />
                        </span>
                    @else
                        {{ $slot }}
                    @endif
                </li>
            </template>
        </ul>
    </div>

    @if($feedbackTxt)
        <p
            id="{{ $selectId }}-feedback"
            class="ui-form-field__feedback {{ $feedbackCls }}"
            @if($hasErr) role="alert" aria-live="polite" @endif
        >
            {{ $feedbackTxt }}
        </p>
    @endif
</div>
