<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'MyCMS Site');
        $this->migrator->add('general.description', 'This is my personal Site built with MyCMS');
    }
};
