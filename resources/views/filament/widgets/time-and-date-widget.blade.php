<x-filament::widget>
    <x-filament::card>
        <div
            x-data="{}"
            x-init="
                setInterval(() => {
                    $wire.updateDateTime()
                }, 1000)
            "
            class="bg-gradient-to-r from-primary-100 to-secondary-100 rounded-lg shadow-md"
        >
            <div class="flex justify-between items-center">
                <div class="text-4xl font-bold text-primary-700 tabular-nums">
                    <span class="text-2xl ml-2 tabular-nums">{{ $currentDateTime->format('h:i') }}</span>
                    <span class="text-xl ml-2">{{ $amPm }}</span>
                </div>
                <div class="text-2xl font-semibold text-secondary-700">
                    {{ $currentDateTime->format('d, M Y') }}
                </div>
            </div>
            <div class="flex justify-between items-center text-sm text-gray-600">
                <div>{{ $dayOfWeek }}</div>
                <div>Day {{ $dayOfYear }} of the year</div>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
