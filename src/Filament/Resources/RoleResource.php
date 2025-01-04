<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Filament\Resources;

use BezhanSalleh\FilamentShield\Resources\RoleResource as FilamentShieldRoleResource;

class RoleResource extends FilamentShieldRoleResource
{
    public static function getNavigationGroup(): ?string
    {
        return 'Admin';
    }
}
