<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Resources;

use BezhanSalleh\FilamentShield\Resources\RoleResource as FilamentShieldRoleResource;

class RoleResource extends FilamentShieldRoleResource
{
    public static function getNavigationGroup(): ?string
    {
        return __('mycms::resources/role.navigation-group');
    }

    public static function getNavigationIcon(): string
    {
        return __('mycms::resources/role.navigation-icon');
    }

    public static function getNavigationLabel(): string
    {
        return __('mycms::resources/role.navigation-label');
    }
}
