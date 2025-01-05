<?php

namespace Bambamboole\MyCms\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Laravel\Prompts\Prompt;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class InstallCommand extends Command
{
    public $signature = 'mycms:install';

    public $description = 'This command installs MyCMS';

    public function handle(Filesystem $filesystem): int
    {
        $this->call('mycms:publish');

        if (confirm('The just published migrations need to be migrated. Do you want to continue?')) {
            $this->call('migrate');
        }

        if (!$filesystem->isDirectory(base_path('app/Providers/Filament'))
            && confirm('It looks like Filament isn\'t installed yet. Do you want to install it and enable MyCMSPlugin?')) {
            $this->call('filament:install', ['--panels' => true, '--force' => true, '--no-interaction' => true]);
            $filePath = base_path('app/Providers/Filament/AdminPanelProvider.php');
            $content = $filesystem->get($filePath);
            $content = str_replace(
                ']);',
                "])\n->plugin(\Bambamboole\MyCms\MyCmsPlugin::make());",
                $content
            );
            $filesystem->put($filePath, $content);
            // interactive is static and is set to false in the last command call
            Prompt::interactive();
        } else {
            $this->info('Filament is already installed. You may enable the MyCMSPlugin manually.');
        }

        $userModel = config('mycms.models.user');

        if (!in_array('Spatie\\Permission\\Traits\\HasRoles', class_uses($userModel))) {
            if (confirm('Do you want to add the HasRoles trait to the User model?')) {
                $loader = require base_path('vendor/autoload.php');
                $filePath = $loader->findFile($userModel);

                $content = $filesystem->get($filePath);
                $content = preg_replace(
                    '/(class\s+User\s+extends\s+[^{]+{)/',
                    "$1\n    use HasRoles;",
                    $content
                );

                $content = preg_replace(
                    '/(use\s+Illuminate\\\Notifications\\\Notifiable;)/',
                    "$1\nuse Spatie\\Permission\\Traits\\HasRoles;",
                    $content
                );

                $filesystem->put($filePath, $content);
            } else {
                $this->info('No changes made to the User model. You need to add the trait on your own.');
            }
        }

        if ($userModel::count() > 0
            && !confirm('There are already users in the database. Do you want to continue?')) {
            $this->info('No user created. All done.');

            return self::SUCCESS;
        }

        $username = text('Enter the admin user name', default: 'admin');
        $email = text('Enter the admin email', default: 'admin@admin.com');
        $password = password('Enter the admin password');

        $user = config('mycms.models.user')::factory()->create([
            'name' => $username,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $this->call('shield:super-admin', ['--user' => $user->id]);
        $this->info('Super admin role created and assigned to user');

        $this->comment('All done');

        return self::SUCCESS;
    }
}