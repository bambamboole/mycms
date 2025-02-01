<?php

namespace Bambamboole\MyCms\Resources\UserResource\Pages;

use Bambamboole\MyCms\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
