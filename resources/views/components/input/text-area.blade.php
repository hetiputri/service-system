@props(['name', 'value' => '', 'required' => false, 'label' => ''])
<div x-data="{ value: @js($value) }" x-modelable="value" class='flex flex-col gap-[8px] w-fit'>
    @if ($label && $required)
        <label>
            <div class="flex gap-[5px]">
                <p class="text-sm font-medium text-gray-700">{{ $label . ' ' }}</p>
                <p class="text-sm font-medium text-red-600">*</p>
            </div>
        </label>
    @endif

    @if ($label && !$required)
        <label x-show={{ !$required }}>
            <p class="text-sm font-medium text-gray-700">{{ $label }}</p>
        </label>
    @endif
    <div class="w-fitl">
        <textarea x-data="{
            resize() {
                $el.style.height = '0px';
                $el.style.height = $el.scrollHeight + 'px'
            }
        }" x-init="resize()" @input="resize()" type="text" placeholder=""
            {{ $attributes->merge(['class' => 'flex w-full h-auto min-h-[50px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50']) }}></textarea>
    </div>
</div>
