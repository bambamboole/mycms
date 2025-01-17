<div>
    @if($this->menuItems->isNotEmpty())
        <ul
            ax-load
            ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('menu', 'mycms') }}"
            x-data="menuBuilder({ parentId: 0 })"
            class="space-y-2"
        >
            @foreach($this->menuItems as $menuItem)
                <x-mycms::menu-item
                    :item="$menuItem"
                />
            @endforeach
        </ul>
    @else
        <x-filament-tables::empty-state
            icon="heroicon-o-document"
            :heading="__('mycms::menu.items.empty.heading')"
        />
    @endif

    <x-filament-actions::modals />
</div>
