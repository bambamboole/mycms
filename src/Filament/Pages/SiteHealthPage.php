<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Pages;

use Bambamboole\MyCms\Filament\Widgets\HealthCheckResultWidget;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Artisan;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\ResultStores\ResultStore;

class SiteHealthPage extends Page
{
    protected static string $view = 'mycms::filament.pages.site-health';

    protected $listeners = ['refresh-component' => '$refresh'];

    protected function getActions(): array
    {
        return [
            Action::make(__('mycms::pages/site-health.buttons.refresh'))
                ->button()
                ->action('refresh'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return parent::getNavigationGroup() ?? __('mycms::pages/site-health.navigation-group');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return parent::getNavigationIcon() ?? __('mycms::pages/site-health.navigation-icon');
    }

    public static function getNavigationLabel(): string
    {
        return __('mycms::pages/site-health.navigation-label');
    }

    public function getHeading(): string|Htmlable
    {
        return __('mycms::pages/site-health.heading');
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('mycms::pages/site-health.subheading');
    }

    protected function getViewData(): array
    {
        $checkResults = app(ResultStore::class)->latestResults();

        return [
            'lastRanAt' => new Carbon($checkResults?->finishedAt),
            'checkResults' => $checkResults,
        ];
    }

    public function refresh(): void
    {
        Artisan::call(RunHealthChecksCommand::class);

        $this->dispatch('refresh-component');

        Notification::make()
            ->title(__('mycms::pages/site-health.notifications.results_refreshed'))
            ->success()
            ->send();
    }

    protected function getHeaderWidgets(): array
    {
        return app(ResultStore::class)
            ->latestResults()
            ->storedCheckResults
            ->map(function ($result) {
                return new WidgetConfiguration(HealthCheckResultWidget::class, ['result' => $result->toArray()]);
            })
            ->toArray();
    }
}
