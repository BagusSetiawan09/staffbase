<x-filament-panels::page>
    <div class="space-y-6">
        <header class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            <h2 class="text-lg font-bold text-gray-950 dark:text-white">Informasi Pemeringkatan</h2>
            <p class="text-sm text-gray-500 mt-1">
                Data di bawah ini dikalkulasi menggunakan metode Simple Additive Weighting (SAW) dan dikelompokkan secara otomatis berdasarkan masing-masing divisi perusahaan.
            </p>
        </header>

        {{ $this->table }}
    </div>
</x-filament-panels::page>