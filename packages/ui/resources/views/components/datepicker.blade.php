{{--
    @component  DatePicker
    @tag        <x-datepicker />
    @props      (see src/Components/DatePicker.php)
--}}
@props([
    'mode'            => 'single',
    'format'          => 'M d, Y',
    'size'            => 'md',
    'color'           => 'primary',
    'value'           => null,
    'label'           => null,
    'hint'            => null,
    'error'           => null,
    'valid'           => false,
    'required'        => false,
    'readonly'        => false,
    'disabled'        => false,
    'weekNumbers'     => false,
    'presets'         => false,
    'disabledDates'   => [],
    'min'             => null,
    'max'             => null,
    'firstDayOfWeek'  => 0,
    'numberOfMonths'  => 1,
    'fixedWeeks'      => false,
    'clearable'       => false,
    'viewControl'     => true,
    'monthControls'   => true,
    'yearControls'    => true,
    'placeholder'     => null,
    'id'              => null,
    'name'            => null,
    'unstyled'        => false,
    'wireModel'       => null,
])

@php
    $c = $component ?? null;
    $datepicker = [
        'mode'            => $c ? $c->mode : $mode,
        'format'          => $c ? $c->format : $format,
        'granularity'     => $c ? $c->granularity() : (in_array($mode, ['month', 'month-range']) ? 'month' : (in_array($mode, ['year', 'year-range']) ? 'year' : 'day')),
        'selectType'      => $c ? $c->selectType() : (in_array($mode, ['range', 'month-range', 'year-range']) ? 'range' : ($mode === 'multiple' ? 'multiple' : 'single')),
        'weekNumbers'     => $c ? $c->weekNumbers : $weekNumbers,
        'firstDayOfWeek'  => (int) ($c ? $c->firstDayOfWeek : $firstDayOfWeek),
        'numberOfMonths'  => (int) ($c ? $c->numberOfMonths : $numberOfMonths),
        'fixedWeeks'      => $c ? $c->fixedWeeks : $fixedWeeks,
        'viewControl'     => $c ? $c->viewControl : $viewControl,
        'monthControls'   => $c ? $c->monthControls : $monthControls,
        'yearControls'    => $c ? $c->yearControls : $yearControls,
        'clearable'       => $c ? $c->clearable : $clearable,
        'min'             => $c ? $c->min : $min,
        'max'             => $c ? $c->max : $max,
        'disabledDates'   => array_values($c ? $c->disabledDates : $disabledDates),
        'presets'         => $c ? $c->presetsConfig() : ($presets === true ? [
            ['label' => 'Today', 'value' => now()->toDateString()],
            ['label' => 'Yesterday', 'value' => now()->subDay()->toDateString()],
            ['label' => 'Last 7 days', 'start' => now()->subDays(6)->toDateString(), 'end' => now()->toDateString()],
            ['label' => 'Last 30 days', 'start' => now()->subDays(29)->toDateString(), 'end' => now()->toDateString()],
            ['label' => 'This month', 'start' => now()->startOfMonth()->toDateString(), 'end' => now()->endOfMonth()->toDateString()],
        ] : []),
        'readonly'        => $c ? $c->readonly : $readonly,
        'disabled'        => $c ? $c->disabled : $disabled,
    ];
    $pickerId = $c ? $c->id : ($id ?? ($label ? 'ui-' . \Illuminate\Support\Str::slug($label) : 'ui-' . uniqid()));
    $pickerName = $c ? $c->name : ($name ?? $pickerId);
    $validationCls = $c ? $c->validationClass() : (!empty($error) ? 'ui-field--error' : ($valid ? 'ui-field--valid' : ($readonly ? 'ui-field--readonly' : '')));
    $pickerClasses = $unstyled ? '' : ($c ? $c->classes() : "ui-datepicker ui-datepicker--{$size} ui-datepicker--{$color} ui-datepicker--{$mode}" . ($weekNumbers ? ' ui-datepicker--week-numbers' : '') . ($disabled ? ' ui-datepicker--disabled' : '') . ($readonly ? ' ui-datepicker--readonly' : ''));
    $initialView = $c ? $c->initialView() : $datepicker['granularity'];
    $initialViewMonth = $c ? $c->initialViewMonth() : ((int) now()->format('n') - 1);
    $initialViewYear = $c ? $c->initialViewYear() : ((int) now()->format('Y'));
    $initialSelected = $c ? $c->initialSelected() : $value;
@endphp

<div class="{{ $unstyled ? '' : 'ui-form-field ' . $validationCls }}">

    @if($label ?? false)
        <label for="{{ $pickerId }}" class="ui-form-field__label">
            {{ $label }}
            @if($required)
                <span class="ui-form-field__required" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div
        class="{{ $pickerClasses }}"
        {{ $attributes->merge(['class' => $unstyled ? '' : '']) }}
        x-data="{
            ...@js($datepicker),
            open: false,
            view: @js($initialView),
            viewMonth: @js($initialViewMonth),
            viewYear: @js($initialViewYear),
            selected: @js($initialSelected),
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            weekDayNames: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

            pad(n) { return n < 10 ? '0' + n : '' + n; },
            iso(y, m, d) { return y + '-' + this.pad(m + 1) + '-' + this.pad(d); },
            isoMonth(y, m) { return y + '-' + this.pad(m + 1); },
            todayIso() { const t = new Date(); return this.iso(t.getFullYear(), t.getMonth(), t.getDate()); },
            parseKey(key) {
                if (key === null || key === undefined || key === '') return null;
                const parts = String(key).split('-').map(Number);
                return { y: parts[0], m: (parts[1] || 1) - 1, d: parts[2] || 1 };
            },
            isDayGran() { return this.granularity === 'day'; },
            isMonthGran() { return this.granularity === 'month'; },
            isYearGran() { return this.granularity === 'year'; },
            isRange() { return this.selectType === 'range'; },
            isMultiple() { return this.selectType === 'multiple'; },

            toggle() {
                if (this.readonly || this.disabled) return;
                this.open = !this.open;
                if (this.open) this.resetToSelection();
            },
            resetToSelection() {
                const key = this.firstSelectedKey();
                if (key) {
                    const p = this.parseKey(key);
                    this.viewYear = p.y;
                    this.viewMonth = p.m;
                }
            },
            firstSelectedKey() {
                if (this.isRange()) return this.selected && this.selected.start ? this.selected.start : null;
                if (this.isMultiple()) return Array.isArray(this.selected) && this.selected.length ? this.selected[0] : null;
                return this.selected || null;
            },

            cycleView() {
                if (!this.viewControl || !this.isDayGran()) return;
                this.view = this.view === 'day' ? 'month' : (this.view === 'month' ? 'year' : 'day');
            },

            previous() {
                if (this.view === 'year') { this.viewYear -= 12; return; }
                if (this.view === 'month') { this.viewYear -= 1; return; }
                let m = this.viewMonth - this.numberOfMonths;
                let y = this.viewYear;
                while (m < 0) { m += 12; y -= 1; }
                this.viewMonth = m;
                this.viewYear = y;
            },
            next() {
                if (this.view === 'year') { this.viewYear += 12; return; }
                if (this.view === 'month') { this.viewYear += 1; return; }
                let m = this.viewMonth + this.numberOfMonths;
                let y = this.viewYear;
                while (m > 11) { m -= 12; y += 1; }
                this.viewMonth = m;
                this.viewYear = y;
            },

            get pages() {
                const pages = [];
                for (let i = 0; i < this.numberOfMonths; i++) {
                    let m = this.viewMonth + i;
                    let y = this.viewYear;
                    while (m > 11) { m -= 12; y += 1; }
                    pages.push(this.monthPage(y, m));
                }
                return pages;
            },
            monthPage(y, m) {
                const firstDow = new Date(y, m, 1).getDay();
                const blanks = (firstDow - this.firstDayOfWeek + 7) % 7;
                const total = new Date(y, m + 1, 0).getDate();
                const cells = [];
                for (let i = 0; i < blanks; i++) cells.push(null);
                for (let d = 1; d <= total; d++) cells.push({ key: this.iso(y, m, d), day: d });
                if (this.fixedWeeks) { while (cells.length % 7 !== 0) cells.push(null); }
                const rows = [];
                for (let i = 0; i < cells.length; i += 7) {
                    const days = cells.slice(i, i + 7);
                    const first = days.find(c => c);
                    rows.push({ days: days, week: this.weekNumbers && first ? this.isoWeek(first.key) : null });
                }
                return { year: y, month: m, rows: rows };
            },
            isoWeek(key) {
                const p = this.parseKey(key);
                const date = new Date(Date.UTC(p.y, p.m, p.d));
                const dayNum = date.getUTCDay() || 7;
                date.setUTCDate(date.getUTCDate() + 4 - dayNum);
                const yearStart = new Date(Date.UTC(date.getUTCFullYear(), 0, 1));
                return Math.ceil((((date - yearStart) / 86400000) + 1) / 7);
            },
            get weekdayHeader() {
                return this.weekDayNames.slice(this.firstDayOfWeek).concat(this.weekDayNames.slice(0, this.firstDayOfWeek));
            },

            get yearPageStart() { return Math.floor(this.viewYear / 12) * 12; },
            get yearCells() {
                const arr = [];
                const s = this.yearPageStart;
                for (let i = 0; i < 12; i++) arr.push(s + i);
                return arr;
            },

            isDisabled(key) {
                if (key === null || key === undefined) return true;
                if (Array.isArray(this.disabledDates) && this.disabledDates.includes(key)) return true;
                if (this.min && key < this.min) return true;
                if (this.max && key > this.max) return true;
                return false;
            },
            isSelected(key) {
                if (this.isMultiple()) return Array.isArray(this.selected) && this.selected.includes(key);
                if (this.isRange()) return !!(this.selected && (this.selected.start === key || this.selected.end === key));
                return this.selected === key;
            },
            isInRange(key) {
                if (!this.isRange() || !this.selected || !this.selected.start || !this.selected.end) return false;
                return key > this.selected.start && key < this.selected.end;
            },
            selectCell(key) {
                if (this.isDisabled(key)) return;
                if (this.isMultiple()) {
                    this.selected = (this.selected || []).includes(key)
                        ? this.selected.filter(k => k !== key)
                        : [...(this.selected || []), key];
                    return;
                }
                if (this.isRange()) {
                    if (!this.selected) this.selected = { start: null, end: null };
                    if (!this.selected.start) {
                        this.selected = { start: key, end: null };
                        return;
                    }
                    if (!this.selected.end) {
                        this.selected = key < this.selected.start
                            ? { start: key, end: this.selected.start }
                            : { start: this.selected.start, end: key };
                        this.open = false;
                        return;
                    }
                    this.selected = { start: key, end: null };
                    return;
                }
                this.selected = key;
                this.open = false;
            },
            clickMonth(m) {
                if (this.isDayGran()) {
                    this.viewMonth = m;
                    this.view = 'day';
                    return;
                }
                const key = this.isoMonth(this.viewYear, m);
                if (this.isDisabled(key)) return;
                this.selectCell(key);
                if (this.isRange() && this.selected && this.selected.start && this.selected.end) this.open = false;
            },
            clickYear(y) {
                if (!this.isYearGran()) {
                    this.viewYear = y;
                    this.view = 'month';
                    return;
                }
                const key = String(y);
                if (this.isDisabled(key)) return;
                this.selectCell(key);
                if (this.isRange() && this.selected && this.selected.start && this.selected.end) this.open = false;
            },
            cellClass(cell) {
                const classes = ['ui-datepicker__cell'];
                if (this.isSelected(cell.key)) classes.push('ui-datepicker__cell--selected');
                else if (this.isInRange(cell.key)) classes.push('ui-datepicker__cell--in-range');
                if (cell.key === this.todayIso()) classes.push('ui-datepicker__cell--today');
                if (this.isDisabled(cell.key)) classes.push('ui-datepicker__cell--disabled');
                return classes.join(' ');
            },
            monthCellClass(m) {
                const key = this.isoMonth(this.viewYear, m);
                const classes = ['ui-datepicker__cell'];
                if (this.isSelected(key)) classes.push('ui-datepicker__cell--selected');
                else if (this.isInRange(key)) classes.push('ui-datepicker__cell--in-range');
                if (this.isMonthGran() && this.isDisabled(key)) classes.push('ui-datepicker__cell--disabled');
                return classes.join(' ');
            },
            yearCellClass(y) {
                const key = String(y);
                const classes = ['ui-datepicker__cell'];
                if (this.isSelected(key)) classes.push('ui-datepicker__cell--selected');
                else if (this.isInRange(key)) classes.push('ui-datepicker__cell--in-range');
                if (this.isYearGran() && this.isDisabled(key)) classes.push('ui-datepicker__cell--disabled');
                return classes.join(' ');
            },

            applyPreset(p) {
                if (this.isRange()) {
                    this.selected = { start: p.start || null, end: p.end || null };
                } else if (this.isMultiple()) {
                    this.selected = p.dates || this.expandRange(p.start, p.end);
                } else {
                    this.selected = p.value || p.start || null;
                }
                if (!this.isMultiple()) this.open = false;
            },
            expandRange(start, end) {
                if (!start || !end) return [];
                const s = this.parseKey(start);
                const e = this.parseKey(end);
                const list = [];
                const cur = new Date(s.y, s.m, s.d);
                const last = new Date(e.y, e.m, e.d);
                while (cur <= last) {
                    list.push(this.iso(cur.getFullYear(), cur.getMonth(), cur.getDate()));
                    cur.setDate(cur.getDate() + 1);
                }
                return list;
            },
            isPresetActive(p) {
                if (this.isRange()) {
                    return !!(this.selected && this.selected.start === p.start && this.selected.end === p.end);
                }
                if (this.isMultiple()) {
                    return Array.isArray(this.selected) && Array.isArray(p.dates) &&
                        this.selected.length === p.dates.length &&
                        p.dates.every(k => this.selected.includes(k));
                }
                return this.selected === (p.value || p.start || null);
            },

            clear() {
                if (this.isRange()) this.selected = { start: null, end: null };
                else if (this.isMultiple()) this.selected = [];
                else this.selected = null;
                if (!this.isRange() && !this.isMultiple()) this.open = false;
            },

            formatKey(key) {
                if (!key) return '';
                if (this.isYearGran()) return String(key);
                const p = this.parseKey(key);
                if (this.isMonthGran()) return this.monthNames[p.m] + ' ' + p.y;
                return this.tokenFormat(p.y, p.m, p.d);
            },
            tokenFormat(y, m, d) {
                const date = new Date(y, m, d);
                let s = this.format;
                const year = String(y);
                const mm = this.pad(m + 1);
                const dd = this.pad(d);
                const shortMonth = this.monthNames[m].slice(0, 3);
                const longMonth = this.monthNames[m];
                const dayName = this.weekDayNames[date.getDay()];
                s = s.replace(/YYYY/g, year)
                     .replace(/MMMM/g, longMonth)
                     .replace(/MM/g, mm)
                     .replace(/DD/g, dd)
                     .replace(/M/g, shortMonth)
                     .replace(/D/g, dayName)
                     .replace(/d/g, String(d));
                return s;
            },
            get displayValue() {
                if (this.isRange()) {
                    const s = this.selected ? this.selected.start : null;
                    const e = this.selected ? this.selected.end : null;
                    if (s && e) return this.formatKey(s) + ' - ' + this.formatKey(e);
                    if (s) return this.formatKey(s);
                    return '';
                }
                if (this.isMultiple()) {
                    return (this.selected || []).map(k => this.formatKey(k)).join(', ');
                }
                return this.formatKey(this.selected);
            },
            get wireValue() {
                if (this.isRange()) {
                    const s = this.selected ? this.selected.start : null;
                    const e = this.selected ? this.selected.end : null;
                    if (s && e) return s + ' - ' + e;
                    return s || '';
                }
                if (this.isMultiple()) return (this.selected || []).join(',');
                return this.selected || '';
            }
        }"
        @keydown.escape.window="open = false"
        @click.outside="open = false"
    >
        {{-- Trigger --}}
        <div class="ui-datepicker__trigger" @click="toggle()">
            <x-icon name="calendar" size="sm" class="ui-datepicker__icon" aria-hidden="true" />
            <input
                type="text"
                readonly
                :value="displayValue"
                id="{{ $pickerId }}"
                name="{{ $pickerName }}"
                placeholder="{{ $placeholder }}"
                class="ui-datepicker__input"
                aria-haspopup="dialog"
                aria-expanded="open ? 'true' : 'false'"
                aria-required="{{ $required ? 'true' : 'false' }}"
                aria-invalid="{{ ($c ? $c->hasError() : !empty($error)) ? 'true' : 'false' }}"
                aria-describedby="{{ $c ? $c->describedById() : ((!empty($error) || !empty($hint)) ? $pickerId . '-feedback' : null) }}"
                @if($disabled) disabled @endif
            />
            @if($clearable)
                <button
                    type="button"
                    class="ui-datepicker__clear"
                    @click.stop="clear()"
                    tabindex="-1"
                    aria-label="Clear date"
                >
                    <x-icon name="x" size="sm" class="ui-datepicker__icon" aria-hidden="true" />
                </button>
            @endif
            <x-icon name="chevron-down" size="sm" class="ui-datepicker__chevron" aria-hidden="true" />
        </div>

        @if($wireModel)
            <input
                type="hidden"
                x-ref="hiddenInput"
                :value="wireValue"
                {{ $c ? $c->wireModelAttribute() : "wire:model.lazy=\"{$wireModel}\"" }}
                x-effect="$refs.hiddenInput.value = wireValue; $refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true })); $refs.hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));"
            />
        @endif

        {{-- Popover --}}
        <div x-show="open" x-cloak x-transition.opacity class="ui-datepicker__popover" role="dialog" aria-label="Date picker">
            <div class="ui-datepicker__popover-inner">

                @if(! empty($datepicker['presets']))
                    <div class="ui-datepicker__presets">
                        <template x-for="(p, i) in presets" :key="i">
                            <button
                                type="button"
                                class="ui-datepicker__preset"
                                :class="{ 'ui-datepicker__preset--active': isPresetActive(p) }"
                                @click="applyPreset(p)"
                                x-text="p.label"
                            ></button>
                        </template>
                    </div>
                @endif

                <div class="ui-datepicker__calendar">

                    {{-- Header --}}
                    <div class="ui-datepicker__header">
                        <button type="button" class="ui-datepicker__nav" @click="previous()" x-show="monthControls || view === 'year'" aria-label="Previous">
                            <x-icon name="chevron-left" size="sm" aria-hidden="true" />
                        </button>

                        <button type="button" class="ui-datepicker__heading" @click="cycleView()" :disabled="!viewControl" x-show="view !== 'year'">
                            <template x-if="view === 'day'">
                                <span>
                                    <span x-text="monthNames[viewMonth]" class="ui-datepicker__heading-label"></span>
                                    <span x-text="viewYear" class="ui-datepicker__heading-label ui-datepicker__heading-label--year"></span>
                                </span>
                            </template>
                            <span x-show="view === 'month'" x-text="viewYear"></span>
                        </button>

                        <button type="button" class="ui-datepicker__heading" @click="cycleView()" :disabled="!viewControl" x-show="view === 'year'">
                            <span x-text="yearPageStart + ' - ' + (yearPageStart + 11)"></span>
                        </button>

                        <button type="button" class="ui-datepicker__nav" @click="next()" x-show="monthControls || view === 'year'" aria-label="Next">
                            <x-icon name="chevron-right" size="sm" aria-hidden="true" />
                        </button>
                    </div>

                    {{-- Day view --}}
                    <template x-if="view === 'day'">
                        <div class="ui-datepicker__months">
                            <template x-for="(page, i) in pages" :key="i">
                                <div class="ui-datepicker__month">
                                    <div class="ui-datepicker__weekdays">
                                        <div class="ui-datepicker__weeknum-head" x-show="weekNumbers"></div>
                                        <template x-for="(d, j) in weekdayHeader" :key="j">
                                            <div class="ui-datepicker__weekday" x-text="d"></div>
                                        </template>
                                    </div>
                                    <template x-for="(row, ri) in page.rows" :key="ri">
                                        <div class="ui-datepicker__row">
                                            <div class="ui-datepicker__weeknum" x-show="weekNumbers" x-text="row.week"></div>
                                            <template x-for="(cell, ci) in row.days" :key="ci">
                                                <template x-if="cell">
                                                    <button
                                                        type="button"
                                                        class="ui-datepicker__cell"
                                                        :class="cellClass(cell)"
                                                        @click="selectCell(cell.key)"
                                                        :disabled="isDisabled(cell.key)"
                                                        x-text="cell.day"
                                                        tabindex="-1"
                                                    ></button>
                                                </template>
                                                <template x-if="!cell">
                                                    <span class="ui-datepicker__cell ui-datepicker__cell--blank"></span>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Month view --}}
                    <template x-if="view === 'month'">
                        <div class="ui-datepicker__grid ui-datepicker__grid--month">
                            <template x-for="(mname, m) in monthNames" :key="m">
                                <button
                                    type="button"
                                    class="ui-datepicker__cell"
                                    :class="monthCellClass(m)"
                                    @click="clickMonth(m)"
                                    :disabled="isMonthGran() ? isDisabled(isoMonth(viewYear, m)) : false"
                                    x-text="shortMonths[m]"
                                    tabindex="-1"
                                ></button>
                            </template>
                        </div>
                    </template>

                    {{-- Year view --}}
                    <template x-if="view === 'year'">
                        <div class="ui-datepicker__grid ui-datepicker__grid--year">
                            <template x-for="(y, i) in yearCells" :key="i">
                                <button
                                    type="button"
                                    class="ui-datepicker__cell"
                                    :class="yearCellClass(y)"
                                    @click="clickYear(y)"
                                    :disabled="isYearGran() ? isDisabled(String(y)) : false"
                                    x-text="y"
                                    tabindex="-1"
                                ></button>
                            </template>
                        </div>
                    </template>

                </div>
            </div>
        </div>
    </div>

    @php
        $feedbackTxt = $c ? $c->feedbackText() : ($error ?? $hint ?? null);
        $hasErr = $c ? $c->hasError() : !empty($error);
        $feedbackCls = $c ? $c->feedbackClass() : ($hasErr ? 'ui-form-field__feedback--error' : 'ui-form-field__feedback--hint');
    @endphp
    @if($feedbackTxt)
        <p
            id="{{ $pickerId }}-feedback"
            class="ui-form-field__feedback {{ $feedbackCls }}"
            @if($hasErr) role="alert" aria-live="polite" @endif
        >
            {{ $feedbackTxt }}
        </p>
    @endif

</div>
