<div x-data="customSelect(Object.entries({{ json_encode($options) }}).map(([value, label]) => ({ value, label })), '{{ $selected ?? '' }}')" class="relative w-full" @keydown.arrow-down.prevent="moveDown()"
    @keydown.arrow-up.prevent="moveUp()" @keydown.enter.prevent="selectCurrent()" @keydown.escape="close()"
    @click.outside="close()" tabindex="0">

    <div @click="toggle()"
        class="border rounded px-3 py-2 cursor-pointer flex justify-between items-center bg-white border-gray-300"
        :class="isOpen ? 'border-blue-500 ring-1 ring-blue-500' : ''">

        <span class="truncate" x-text="selectedLabel || '{{ $placeholder ?? 'Pilih...' }}'"></span>

        <span class="text-xs transition-transform duration-200" :class="isOpen ? 'rotate-180' : ''">▼</span>
    </div>

    <div x-show="isOpen" x-cloak class="absolute z-50 w-full bg-white border border-gray-300 rounded mt-1 shadow-lg">

        <div class="p-2 border-b">
            <input x-ref="searchInput" x-model="search" @input="onSearch()" type="text" placeholder="Cari..."
                class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:border-blue-500">
        </div>

        <ul x-ref="listbox" class="max-h-60 overflow-y-auto">
            <template x-for="(option, index) in filteredOptions" :key="option.value">
                <li @click="selectOption(index)" @mouseenter="focusedIndex = index"
                    :class="{
                        'bg-blue-600 text-white': focusedIndex === index,
                        'bg-blue-50 text-blue-900': selectedValue == option.value && focusedIndex !== index
                    }"
                    class="px-3 py-2 cursor-pointer text-sm" x-text="option.label">
                </li>
            </template>
            <li x-show="filteredOptions.length === 0" class="px-3 py-2 text-gray-500 text-sm italic">
                Tidak ada hasil
            </li>
        </ul>
    </div>

    <input type="hidden" name="{{ $name ?? 'select' }}" :value="selectedValue">
</div>

<script>
    function customSelect(mappedOptions, defaultValue = '') {
        return {
            options: mappedOptions,
            filteredOptions: mappedOptions,
            isOpen: false,
            focusedIndex: 0,
            search: '',
            selectedValue: defaultValue,
            selectedLabel: mappedOptions.find(o => o.value == defaultValue)?.label || '',

            open() {
                this.isOpen = true;
                this.search = '';
                this.filteredOptions = this.options;

                const idx = this.filteredOptions.findIndex(o => o.value == this.selectedValue);
                this.focusedIndex = idx >= 0 ? idx : 0;

                this.$nextTick(() => {
                    this.$refs.searchInput?.focus();
                    this.scrollToFocused();
                });
            },

            close() {
                this.isOpen = false;
            },
            toggle() {
                this.isOpen ? this.close() : this.open();
            },

            onSearch() {
                this.filteredOptions = this.options.filter(o =>
                    o.label.toLowerCase().includes(this.search.toLowerCase())
                );
                this.focusedIndex = 0;
                this.$nextTick(() => this.scrollToFocused());
            },

            moveDown() {
                if (!this.isOpen) return this.open();
                this.focusedIndex = (this.focusedIndex + 1) % this.filteredOptions.length;
                this.scrollToFocused();
            },

            moveUp() {
                if (!this.isOpen) return this.open();
                this.focusedIndex = (this.focusedIndex - 1 + this.filteredOptions.length) % this.filteredOptions.length;
                this.scrollToFocused();
            },

            scrollToFocused() {
                const listbox = this.$refs.listbox;
                const items = listbox?.querySelectorAll('li');
                if (items && items[this.focusedIndex]) {
                    items[this.focusedIndex].scrollIntoView({
                        block: 'nearest'
                    });
                }
            },

            selectCurrent() {
                if (this.filteredOptions[this.focusedIndex]) {
                    this.selectOption(this.focusedIndex);
                }
            },

            selectOption(index) {
                const option = this.filteredOptions[index];
                if (option) {
                    this.selectedValue = option.value;
                    this.selectedLabel = option.label;
                    this.close();
                }
            }
        }
    }
</script>
