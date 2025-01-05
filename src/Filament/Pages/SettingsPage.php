<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class SettingsPage extends Page
{
    use CanUseDatabaseTransactions;
    use HasUnsavedDataChangesAlert;

    protected static string $view = 'mycms::filament.pages.settings-page';

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
            ->title(__('mycms::pages/settings.notifications.saved', ['group' => Str::headline($setting::group())]))
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

    public static function getNavigationGroup(): ?string
    {
        return parent::getNavigationGroup() ?? __('mycms::pages/settings.navigation-group');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return parent::getNavigationIcon() ?? __('mycms::pages/settings.navigation-icon');
    }

    public static function getNavigationLabel(): string
    {
        return __('mycms::pages/settings.navigation-label');
    }

    public function getHeading(): string|Htmlable
    {
        return __('mycms::pages/settings.heading');
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('mycms::pages/settings.subheading');
    }
}
