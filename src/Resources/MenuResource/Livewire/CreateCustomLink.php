<?php

declare(strict_types=1);

namespace Bambamboole\MyCms\Resources\MenuResource\Livewire;

use Bambamboole\MyCms\Models\Menu;
use Bambamboole\MyCms\Resources\MenuResource\LinkTarget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateCustomLink extends Component implements HasForms
{
    use InteractsWithForms;

    public Menu $menu;

    public string $title = '';

    public string $url = '';

    public string $target = LinkTarget::Self->value;

    public function save(): void
    {
        $this->validate([
            'title' => ['required', 'string'],
            'url' => ['required', 'string'],
            'target' => ['required', 'string', Rule::in(LinkTarget::cases())],
        ]);

        $this->menu
            ->menuItems()
            ->create([
                'title' => $this->title,
                'url' => $this->url,
                'target' => $this->target,
                'order' => $this->menu->menuItems->max('order') + 1,
            ]);

        Notification::make()
            ->title(__('mycms::menu.notifications.created.title'))
            ->success()
            ->send();

        $this->reset('title', 'url', 'target');
        $this->dispatch('menu:created');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('mycms::menu.form.title'))
                    ->required(),
                TextInput::make('url')
                    ->label(__('mycms::menu.form.url'))
                    ->required(),
                Select::make('target')
                    ->label(__('mycms::menu.open_in.label'))
                    ->options(LinkTarget::class)
                    ->default(LinkTarget::Self),
            ]);
    }

    public function render(): View
    {
        return view('mycms::menu.livewire.create-custom-link');
    }
}
