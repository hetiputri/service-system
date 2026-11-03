<div 
x-data="{
    open: false,
    fileUrl: '{{ $url }}',
    get fileType() {
        if (!this.fileUrl) return null
        return 'pdf'
    }
}"
    class="inline-block"
>

@if($url && $theme !='default')
    <button 
        @click="open = true"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg transition
               bg-blue-600 text-white hover:bg-blue-700
               dark:bg-blue-500 dark:hover:bg-blue-600">

        <!-- Document Icon -->
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-4 h-4"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 7h10M7 11h10M7 15h6m5 4H6a2 2 0 01-2-2V5a2
                     2 0 012-2h8l6 6v10a2 2 0 01-2 2z" />
        </svg>

        Lihat
    </button>

    <!-- Modal -->

    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 backdrop-blur-sm"
    >
        <div 
            @click.away="open = false"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-11/12 max-w-5xl h-[95vh] p-4 flex flex-col"
        >

            <!-- Header -->
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    Preview Dokumen
                </h2>

                <button 
                    @click="open = false"
                    class="text-gray-500 hover:text-red-500"
                >
                    ✕
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-hidden rounded-lg border dark:border-gray-700">

                <!-- PDF -->
                <template x-if="fileType === 'pdf'">
                    <iframe 
                        :src="fileUrl"
                        class="w-full h-full"
                    ></iframe>
                </template>

                <!-- Image -->
                <template x-if="fileType === 'image'">
                    <img 
                        :src="fileUrl"
                        class="w-full h-full object-contain bg-black"
                    />
                </template>

                <!-- Other File -->
                <template x-if="fileType === 'other'">
                    <div class="flex items-center justify-center h-full">
                        <a 
                            :href="fileUrl"
                            target="_blank"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Download File
                        </a>
                    </div>
                </template>

            </div>

        </div>
    </div>

@elseif ($url && $theme =='default')
<button 
        @click="open = true"
        class="text-xs border border-gray-300 dark:border-gray-600 px-3 py-1 rounded-lg bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200transitionss">
        Lihat
    </button>
<!-- Modal -->

    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 backdrop-blur-sm"
    >
        <div 
            @click.away="open = false"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-11/12 max-w-5xl h-[85vh] p-4 flex flex-col"
        >

            <!-- Header -->
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    Preview Dokumen
                </h2>

                <button 
                    @click="open = false"
                    class="text-gray-500 hover:text-red-500"
                >
                    ✕
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-hidden rounded-lg border dark:border-gray-700">

                <!-- PDF -->
                <template x-if="fileType === 'pdf'">
                    <iframe 
                        :src="fileUrl"
                        class="w-full h-full"
                    ></iframe>
                </template>

                <!-- Image -->
                <template x-if="fileType === 'image'">
                    <img 
                        :src="fileUrl"
                        class="w-full h-full object-contain bg-black"
                    />
                </template>

                <!-- Other File -->
                <template x-if="fileType === 'other'">
                    <div class="flex items-center justify-center h-full">
                        <a 
                            :href="fileUrl"
                            target="_blank"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Download File
                        </a>
                    </div>
                </template>

            </div>

        </div>
    </div>            

@else

    <span
        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg
               bg-gray-200 text-gray-500
               dark:bg-gray-700 dark:text-gray-400">

        Tidak ada
    </span>

@endif

</div>