<x-filament-widgets::widget>
    <x-filament::section
        heading="Zuletzt bearbeitet"
        description="Die letzten Änderungen über alle Inhaltsbereiche hinweg."
    >
        <div class="space-y-3">
            @forelse ($this->getItems() as $item)
                <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 px-4 py-3 dark:border-white/10">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <x-filament::icon
                                :icon="$item['icon']"
                                class="h-4 w-4"
                            />
                            <span>{{ $item['label'] }}</span>
                            <span @class([
                                'inline-flex rounded-full px-2 py-0.5 text-xs',
                                'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' => $item['is_active'],
                                'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300' => ! $item['is_active'],
                            ])>
                                {{ $item['is_active'] ? 'Aktiv' : 'Inaktiv' }}
                            </span>
                        </div>

                        <div class="mt-1 truncate font-medium text-gray-950 dark:text-white">
                            {{ $item['title'] }}
                        </div>

                        <div class="mt-1 truncate text-sm text-gray-500 dark:text-gray-400">
                            {{ $item['path'] }}
                        </div>
                    </div>

                    <div class="shrink-0 text-sm text-gray-500 dark:text-gray-400">
                        {{ $item['updated_at'] }}
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 px-4 py-8 text-center text-sm text-gray-500 dark:border-white/10 dark:text-gray-400">
                    Noch keine Inhalte vorhanden.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
