<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class SettingsPage extends Page
{
    use CanUseDatabaseTransactions;
    use HasUnsavedDataChangesAlert;
    use InteractsWithFormActions;

    protected static string $view = 'mycms::filament.pages.settings-page';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $settings = config('mycms.settings');

        $data = [];
        foreach ($settings as $setting) {
            $data = array_merge($data, app($setting)->toArray());
        }
        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        $settings = config('mycms.settings');
        $tabs = array_map(
            fn (string $setting) => Tabs\Tab::make(str_replace('Settings', '', class_basename($setting)))->schema(app($setting)->form()),
            $settings,
        );

        return $form->schema([
            Tabs::make()->tabs($tabs),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = config('mycms.settings');
        foreach ($settings as $setting) {
            $setting = app($setting);
            $setting->submit($data);
        }
        Notification::make()
            ->success()
            ->title('Saved!')
            ->send();
    }

    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Save')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->statePath('data')
                    ->inlineLabel($this->hasInlineLabels()),
            ),
        ];
    }
}
