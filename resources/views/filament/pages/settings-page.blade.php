<x-filament-panels::page x-data="{ activeTab: 'general' }">
    <x-filament::tabs>
        @foreach($this->getSettings() as $settings)
            <x-filament::tabs.item
                icon="heroicon-o-cog-6-tooth"
                alpine-active="activeTab === '{{ $settings::group() }}'"
                x-on:click="activeTab = '{{ $settings::group() }}'">
                {{ \Illuminate\Support\Str::headline($settings::group()) }}
            </x-filament::tabs.item>

        @endforeach
    </x-filament::tabs>
    @foreach($this->getSettings() as $settings)
        @php
            $formProp = $settings::group() . 'SettingsForm';
        @endphp

        <template x-if="activeTab === '{{$settings::group()}}'">
            <x-filament-panels::form wire:submit="save('{{$settings::group()}}')">
                {{ $this->$formProp }}
                <div>
                    <x-filament::button type="submit" size="sm">
                        Submit
                    </x-filament::button>
                </div>
            </x-filament-panels::form>
        </template>
    @endforeach
</x-filament-panels::page>
