<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Settings;

interface MyCmsSettingsInterface
{
    public function form(): array;

    public function submit(array $payload): void;

    public static function group(): string;

    public static function icon(): string;
}
