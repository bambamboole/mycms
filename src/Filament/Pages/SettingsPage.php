<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Page;

class SettingsPage extends Page
{
    use CanUseDatabaseTransactions;
    use HasUnsavedDataChangesAlert;

    protected static string $view = 'mycms::filament.pages.settings-page';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'Settings';

    protected ?string $heading = 'Settings';

    protected ?string $subheading = 'Configure your MyCMS site';

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    public function getSettings()
    {
        return collect(config('mycms.settings'));
    }

    protected function fillForm(): void
    {
        foreach ($this->getSettings() as $setting) {
            $formName = $setting::group().'SettingsForm';
            $this->$formName->fill(app($setting)->toArray());
        }
    }

    public function save($group): void
    {
        $setting = $this->getSettings()->first(fn ($setting) => $setting::group() === $group);
        $instance = app($setting);
        $formName = $setting::group().'SettingsForm';
        $instance->fill($this->$formName->getState());
        $instance->save();
        Notification::make()
            ->success()
            ->title($setting::group().' settings saved!')
            ->send();
    }

    protected function getForms(): array
    {
        return $this->getSettings()
            ->mapWithKeys(fn ($setting) => [$setting::group().'SettingsForm' => $this->makeForm()
                ->schema(app($setting)->form())
                ->statePath('data.'.$setting::group())])
            ->toArray();
    }
}
