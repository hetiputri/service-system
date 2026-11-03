<div x-data="customSelect(@js($options))" x-modelable="selectedValue" {{ $attributes->class(['w-full relative']) }}
    @keydown.arrow-down.prevent="moveDown()" @keydown.arrow-up.prevent="moveUp()" @keydown.enter.prevent="selectCurrent()"
    @keydown.escape="close()" @click.outside="close()" tabindex="0">

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

    <div class="relative border border-gray-300
                rounded-[8px]
                focus:ring-2 focus:ring-blue-500
                shadow-xs"
        x-bind:class="{
            'border-blue-500 ring-1 ring-blue-500': isOpen
        }">
        <div @click="toggle()"
            class="
                w-[95%]
                bg-white
                p-2
                flex items-center
                cursor-pointer
                rounded-[8px]
            ">
            <span class="text-sm text-gray-700" x-text="getSelectedLabel() || 'Pilih {{ $label }}'">
            </span>
        </div>

        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
            <x-icon name="angle-down" class="w-[20px] h-[20px]"
                x-bind:style="`transform: rotate(${isOpen ? 180 : 0}deg); transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);`" />
        </div>
    </div>

    <div x-show="isOpen" x-cloak x-transition
        class="
            mt-2
            bg-white
            border border-gray-200
            rounded-xl
            shadow-md
            absolute
            z-50
            w-full
        ">

        <input x-ref="searchInput" type="text" x-model="search" @input="onSearch()"
            class="w-full p-2 border-b outline-none" placeholder="Cari...">

        <div x-ref="listbox" class="max-h-60 overflow-y-auto">
            <template x-for="(option, index) in filteredOptions" :key="option.value">
                <div @click="selectOption(index)" @mouseenter="focusedIndex = index"
                    x-bind:class="{
                        'bg-gray-100': focusedIndex === index
                    }"
                    class="
                        px-4 py-2
                        text-sm
                        text-gray-700
                        hover:bg-gray-100
                        cursor-pointer
                        flex
                        justify-between
                    ">
                    <p x-text="option.label"></p>
                    <x-icon x-show="selectedValue == option.value" name="check" />
                </div>
            </template>

            <div x-show="filteredOptions.length === 0" class="p-2 text-gray-400">
                Tidak ditemukan
            </div>
        </div>
    </div>

    <input type="hidden" name="{{ $name ?? 'select' }}" x-bind:value="selectedValue">

</div>

@once
    <script>
        function customSelect(data) {
            return {
                options: Object.entries(data).map(([value, label]) => ({
                    value,
                    label
                })),
                filteredOptions: [],
                isOpen: false,
                focusedIndex: 0,
                search: '',
                selectedValue: null,
                selectedLabel: '',

                init() {
                    this.filteredOptions = this.options
                },

                getSelectedLabel() {
                    return this.options.find(o => o.value == this.selectedValue)?.label || ''
                },

                open() {
                    this.isOpen = true
                    this.search = ''
                    this.filteredOptions = this.options

                    const idx = this.filteredOptions.findIndex(o => o.value == this.selectedValue)
                    this.focusedIndex = idx >= 0 ? idx : 0

                    this.$nextTick(() => {
                        this.$refs.searchInput?.focus()
                        this.scrollToFocused()
                    })
                },

                close() {
                    this.isOpen = false
                },

                toggle() {
                    this.isOpen ? this.close() : this.open()
                },

                onSearch() {
                    this.filteredOptions = this.options.filter(o =>
                        o.label.toLowerCase().includes(this.search.toLowerCase())
                    )

                    this.focusedIndex = 0
                    this.$nextTick(() => this.scrollToFocused())
                },

                moveDown() {
                    if (!this.isOpen) return this.open()
                    if (this.filteredOptions.length === 0) return

                    this.focusedIndex = (this.focusedIndex + 1) % this.filteredOptions.length
                    this.scrollToFocused()
                },

                moveUp() {
                    if (!this.isOpen) return this.open()
                    if (this.filteredOptions.length === 0) return

                    this.focusedIndex = (this.focusedIndex - 1 + this.filteredOptions.length) % this.filteredOptions.length
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
                    if (this.filteredOptions[this.focusedIndex]) {
                        this.selectOption(this.focusedIndex)
                    }
                },

                selectOption(index) {
                    const option = this.filteredOptions[index]

                    if (option) {
                        if (option.value == this.selectedValue) {
                            this.selectedValue = null
                            this.selectedLabel = null
                        } else {
                            this.selectedValue = option.value
                            this.selectedLabel = option.label
                            this.close()
                        }

                    }
                }
            }
        }
    </script>
@endonce
