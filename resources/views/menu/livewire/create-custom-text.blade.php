<form wire:submit="save">
    <x-filament::section
        :heading="__('mycms::menu.custom_text')"
        :collapsible="true"
        :persist-collapsed="true"
        id="create-custom-text"
    >
        {{ $this->form }}

        <x-slot:footerActions>
            <x-filament::button type="submit">
                {{ __('mycms::menu.resource.actions.add.label') }}
            </x-filament::button>
        </x-slot:footerActions>
    </x-filament::section>
</form>
