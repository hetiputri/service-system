<div x-data="selectFunction(@js($options))" x-modelable="selectedValue"
    {{ $attributes->class(['w-full h-fit flex flex-col gap-[8px]']) }} x-init="$watch('selectedValue', value => {
        if (value === '') {
            selected = ''
        }
    })">
    @if ($label && $required)
        <div>
            <label>
                <div class="flex gap-[5px]">
                    <p class="text-sm font-medium text-gray-700">{{ $label . ' ' }}</p>
                    <p class="text-sm font-medium text-red-600">*</p>
                </div>
            </label>
        </div>
    @endif

    @if ($label && !$required)
        <div>
            <label x-show={{ !$required }}>
                <p class="text-sm font-medium text-gray-700">{{ $label }}</p>
            </label>
        </div>
    @endif

    {{-- Trigger --}}
    <div class="relative" @keydown.arrow-down.prevent="moveDown()" @keydown.arrow-up.prevent="moveUp()"
        @keydown.enter.prevent="selectCurrent()" @keydown.escape="close()" @click.outside="close()" tabindex="0">
        <div @click="toggle()"
            class="
                w-full
                bg-white
                border border-gray-300
                rounded-[8px]
                p-2
                flex items-center
                cursor-pointer
                focus:ring-2 focus:ring-blue-500
                shadow-xs
            ">
            <span class="text-sm text-gray-700 mr-7" x-text="selected || 'Pilih {{ $label }}'"></span>

        </div>


        <div class="absolute
                inset-y-0 right-4 flex items-center pointer-events-none">
            <x-icon name="angle-down" class="w-[20px] h-[20px]" />
        </div>
    </div>


    <div x-show="open" @click.outside="open = false" x-transition
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
        <input type="text" x-model="search" placeholder="Cari..." class="w-full p-2 border-b outline-none">
        <div class="max-h-60
            overflow-y-auto">
            <template x-for="(label, value) in filteredOptions()" :key="value">
                <div @click="selectAction(label, value)"
                    class="
                    px-4 py-2
                    text-sm
                    text-gray-700
                    hover:bg-gray-100
                    cursor-pointer
                    flex
                    justify-between
                "
                    :class="selected === label ? 'bg-gray-100' : 'bg-transparent'">
                    <p x-text="label"></p>
                    <x-icon x-show="selected === label" name="check" />

                </div>
            </template>
            <div x-show="filteredOptions().length === 0" class="p-2 text-gray-400">
                Tidak ditemukan
            </div>
        </div>

        <!-- hidden input -->
        {{-- <input type="hidden" name="toko_id" :value="selectedValue"> --}}

    </div>
</div>

@once
    <script>
        function selectFunction(data) {
            return {
                isOpen: false,
                search: '',
                selected: '',
                data: data,
                selectedValue: null,

                options: Object.entries(data).map(([value, label]) => ({
                    value,
                    label
                })),

                filteredOptions: options,
                focusedIndex: 0,

                selectAction(label, value) {
                    if (this.selected === label) {
                        this.selected = '';
                    } else {
                        this.selected = label;
                    }

                    if (this.selectedValue === value) {
                        this.selectedValue = '';
                    } else {
                        this.selectedValue = value;
                    }
                    this.open = false;
                },

                filteredOptions() {
                    if (this.search === '') return this.data

                    return Object.fromEntries(
                        Object.entries(this.data).filter(([value, label]) =>
                            label.toLowerCase().includes(this.search.toLowerCase())
                        )
                    )
                },
            }
        }
    </script>
@endonce
