<button
    {{ $attributes->class([
        'h-[44px] rounded-[8px] border border-[#D0D5DD] shadow-xs py-[12px] px-[16px] flex justify-center items-center gap-[8px] hover:cursor-pointer hover:opacity-80 disabled:bg-gray-300  disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-gray-300 disabled:border-none',
    ]) }}>
    {{ $slot }}
</button>
