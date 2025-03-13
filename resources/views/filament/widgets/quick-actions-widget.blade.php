
<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-medium">Quick Actions</h2>
        <div class="mt-4 grid grid-cols-2 gap-4">
            @foreach ($this->actions as $action)
                <a href="{{ $action['url'] }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:border-primary-700 focus:ring focus:ring-primary-200 disabled:opacity-25 transition">
                    @svg($action['icon'], 'w-5 h-5 mr-2')
                    {{ $action['label'] }}
                </a>
            @endforeach
        </div>
    </x-filament::card>
</x-filament::widget>
