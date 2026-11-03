@props(['placeholder' => ''])
<div x-data="inputTextFunction()" x-modelable="value" class='flex flex-col gap-[8px] w-full'>
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
    <input type="text"
        {{ $attributes->merge(['class' => 'w-full h-fit text-sm text-gray-700 border border-gray-300 rounded-[8px] p-2 focus:outline-none focus:ring-1 focus:ring-blue-500 shadow-xs']) }}
        x-model="value" placeholder="{{ $placeholder }}" />
</div>

<script>
    function inputTextFunction() {
        return {
            value: null,
        }
    }
</script>
