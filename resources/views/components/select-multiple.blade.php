<div x-data="multipleSelectFunction(@js($options))" x-modelable="selectedValue" {{ $attributes->class(['w-full relative']) }}
    @keydown.arrow-down.prevent="moveDown()" @keydown.arrow-up.prevent="moveUp()" @keydown.enter.prevent="selectCurrent()"
    @keydown.escape="open = false" tabindex="0" x-init="$watch('selectedValue', value => {
        if (value.length === 0) selected = []
    })">

    {{-- LABEL --}}
    @if ($label && $required)
        <div class="mb-[8px]">
            <label>
                <div class="flex gap-[5px]">
                    <p class="text-sm font-medium text-gray-700">{{ $label }}</p>
                    <p class="text-sm font-medium text-red-600">*</p>
                </div>
            </label>
        </div>
    @elseif ($label)
        <div class="mb-[8px]">
            <label>
                <p class="text-sm font-medium text-gray-700">{{ $label }}</p>
            </label>
        </div>
    @endif

    {{-- TRIGGER --}}
    <div class="relative">
        <div @click="toggle()"
            class="
                w-full bg-white border border-gray-300
                rounded-[8px] p-2 flex items-center cursor-pointer
                focus:ring-2 focus:ring-blue-500 shadow-xs
            ">

            <span x-show="selected.length === 0" class="text-sm text-gray-700">
                Pilih {{ $label }}
            </span>

            <div x-show="selected.length > 0" class="grid grid-cols-3 gap-[8px]">
                <template x-for="(item, index) in selected" :key="index">
                    <div
                        class="flex py-0 px-[5px] gap-[8px] items-center border border-gray-300 rounded-[8px] justify-between shadow-xs h-[30px] w-full">
                        <p class="text-sm text-gray-700" x-text="item"></p>
                        <x-button @click.stop="remove(index)" class="!border-0 !p-0">
                            <x-icon name="xmark" class="w-[15px] h-[15px]" />
                        </x-button>
                    </div>
                </template>
            </div>
        </div>

        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
            <x-icon name="angle-down" class="w-[20px] h-[20px]"
                x-bind:style="`transform: rotate(${open ? 180 : 0}deg); transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);`" />
        </div>
    </div>

    {{-- DROPDOWN --}}
    <div x-show="open" @click.outside="open = false" x-transition
        class="mt-2 bg-white border border-gray-200 rounded-xl shadow-md absolute z-50 w-full">

        <input x-ref="searchInput" type="text" x-model="search" @input="onSearch()"
            class="w-full p-2 border-b outline-none text-sm" placeholder="Cari...">

        <div x-ref="listbox" class="max-h-60 overflow-y-auto">
            <template x-for="(option, index) in filteredArray()" :key="option.value">
                <div @click="selectOption(index)" @mouseenter="focusedIndex = index"
                    x-bind:class="{
                        'bg-gray-100': focusedIndex === index
                    }"
                    class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer flex justify-between">
                    <p x-text="option.label"></p>
                    <x-icon x-show="selected.includes(option.label)" name="check" class="w-[20px] h-[20px]" />
                </div>
            </template>

            <div x-show="filteredArray().length === 0" class="p-2 text-gray-400">
                Tidak ditemukan
            </div>
        </div>
    </div>
</div>

<script>
    function multipleSelectFunction(data) {
        return {
            open: false,
            search: '',
            selected: [],
            selectedValue: [],
            focusedIndex: 0,

            options: Object.entries(data).map(([value, label]) => ({
                value,
                label
            })),

            toggle() {
                this.open = !this.open

                if (this.open) {
                    this.$nextTick(() => {
                        this.$refs.searchInput?.focus()
                        this.focusedIndex = 0
                    })
                }
            },

            filteredArray() {
                return this.options.filter(o =>
                    o.label.toLowerCase().includes(this.search.toLowerCase())
                )
            },

            onSearch() {
                this.focusedIndex = 0
                this.$nextTick(() => this.scrollToFocused())
            },

            moveDown() {
                if (!this.open) return this.toggle()

                let items = this.filteredArray()
                if (items.length === 0) return

                this.focusedIndex = (this.focusedIndex + 1) % items.length
                this.scrollToFocused()
            },

            moveUp() {
                if (!this.open) return this.toggle()

                let items = this.filteredArray()
                if (items.length === 0) return

                this.focusedIndex = (this.focusedIndex - 1 + items.length) % items.length
                this.scrollToFocused()
            },

            scrollToFocused() {
                const container = this.$refs.listbox
                const items = container?.children

                if (!items || !items[this.focusedIndex]) return

                const item = items[this.focusedIndex]

                const itemHeight = item.offsetHeight
                const containerHeight = container.offsetHeight

                const itemTop = item.offsetTop
                const itemBottom = itemTop + itemHeight

                const scrollTop = container.scrollTop
                const scrollBottom = scrollTop + containerHeight

                const buffer = itemHeight

                if (itemTop - buffer < scrollTop) {
                    container.scrollTo({
                        top: Math.max(itemTop - buffer, 0),
                        behavior: 'auto'
                    })
                } else if (itemBottom + buffer > scrollBottom) {
                    container.scrollTo({
                        top: itemBottom + buffer - containerHeight,
                        behavior: 'auto'
                    })
                }
            },

            selectCurrent() {
                this.selectOption(this.focusedIndex)
            },

            selectOption(index) {
                const option = this.filteredArray()[index]
                if (!option) return

                this.selectAction(option.label, option.value)
            },

            selectAction(label, value) {
                if (this.selected.includes(label)) {
                    this.selected = this.selected.filter(val => val !== label)
                    this.selectedValue = this.selectedValue.filter(val => val !== value)
                } else {
                    this.selected.push(label)
                    this.selectedValue.push(value)
                }
            },

            remove(index) {
                this.selected.splice(index, 1)
                this.selectedValue.splice(index, 1)
            }
        }
    }
</script>
