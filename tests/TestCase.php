<?php

namespace Saivra\MeUi\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Saivra\MeUi\MeUiServiceProvider;
use Saivra\MeUi\Tests\Fixtures\TestPanelProvider;
use Saivra\MeUi\Tests\Fixtures\User;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            LivewireServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,
            MeUiServiceProvider::class,
            TestPanelProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app->usePublicPath(sys_get_temp_dir().'/meui-public-'.getmypid());
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('session.driver', 'array');
        $app['config']->set('database.default', getenv('MEUI_DB_CONNECTION') ?: 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.connections.pgsql', [
            'driver' => 'pgsql',
            'host' => getenv('MEUI_DB_HOST') ?: '127.0.0.1',
            'port' => getenv('MEUI_DB_PORT') ?: '5432',
            'database' => getenv('MEUI_DB_DATABASE') ?: 'meui_theme_test',
            'username' => getenv('MEUI_DB_USERNAME') ?: 'postgres',
            'password' => getenv('MEUI_DB_PASSWORD') ?: '',
            'charset' => 'utf8', 'prefix' => '', 'search_path' => 'public', 'sslmode' => 'prefer',
        ]);
        $app['config']->set('view.compiled', sys_get_temp_dir().'/meui-views-'.getmypid());
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->app['view']->addNamespace('meui-tests', __DIR__.'/views');
        Filament::setCurrentPanel(Filament::getPanel('meui'));
        Filament::setTenant(null);
    }

    protected function operator(bool $preview = true): User
    {
        $user = User::create(['name' => 'Mara Lind', 'email' => 'mara+'.User::count().'@example.test', 'can_preview' => $preview]);
        $this->actingAs($user);

        return $user;
    }
}
