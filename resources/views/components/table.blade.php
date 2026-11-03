<div class="overflow-x-auto">

    <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700">

        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
            <tr>
                {{ $head }}
            </tr>
        </thead>

        <tbody class="divide-y dark:divide-gray-700">
            {{ $slot }}
        </tbody>

    </table>

</div>
