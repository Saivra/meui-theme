<?php

namespace Saivra\MeUi\Tests;

use Filament\Facades\Filament;
use Filament\Livewire\Sidebar as NativeSidebar;
use Filament\Livewire\Topbar as NativeTopbar;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Saivra\MeUi\Livewire\Sidebar;
use Saivra\MeUi\Livewire\Topbar;
use Saivra\MeUi\MeUiPlugin;
use Saivra\MeUi\Pages\ComponentGallery;

class PluginTest extends TestCase
{
    #[Test]
    public function it_registers_native_shell_components_and_keeps_the_studio_opt_in(): void
    {
        $panel = Panel::make()->id('opt-in')->plugin(MeUiPlugin::make()->density('compact'));

        self::assertSame(Sidebar::class, $panel->getSidebarLivewireComponent());
        self::assertSame(Topbar::class, $panel->getTopbarLivewireComponent());
        self::assertSame('compact', $panel->getPlugin('meui')->getDensity());
        self::assertNotContains(ComponentGallery::class, $panel->getPages());
    }

    #[Test]
    public function it_preserves_the_original_shell_and_dimensions_when_disabled(): void
    {
        $panel = Panel::make()->id('native')->sidebarWidth('22rem')->collapsedSidebarWidth('6rem')
            ->plugin(MeUiPlugin::make()->shell(false));

        self::assertSame(NativeSidebar::class, $panel->getSidebarLivewireComponent());
        self::assertSame(NativeTopbar::class, $panel->getTopbarLivewireComponent());
        self::assertSame('22rem', $panel->getSidebarWidth());
        self::assertSame('6rem', $panel->getCollapsedSidebarWidth());
    }

    #[Test]
    public function it_rejects_unknown_densities(): void
    {
        $this->expectException(InvalidArgumentException::class);
        MeUiPlugin::make()->density('unknown');
    }

    #[Test]
    public function it_does_not_emit_hooks_into_other_panels(): void
    {
        $panel = Filament::getPanel('meui');
        $panel->boot();
        self::assertStringContainsString('meui-root', FilamentView::renderHook(PanelsRenderHook::BODY_START)->toHtml());

        Filament::setCurrentPanel(Panel::make()->id('plain'));

        self::assertStringNotContainsString('meui-root', FilamentView::renderHook(PanelsRenderHook::BODY_START)->toHtml());
        self::assertStringNotContainsString('saivra/meui-theme', FilamentView::renderHook(PanelsRenderHook::STYLES_AFTER)->toHtml());
    }

    #[Test]
    public function it_retains_the_native_authentication_form(): void
    {
        $this->get('/meui/login')->assertOk()->assertSee('meui-root', false)->assertSee('meui-auth-aside', false)
            ->assertSee('wire:submit="authenticate"', false);
    }

    #[Test]
    public function it_can_disable_the_authentication_aside(): void
    {
        $panel = Panel::make()->id('without-aside')->plugin(MeUiPlugin::make()->authAside(false));
        Filament::setCurrentPanel($panel);
        $panel->boot();

        self::assertStringNotContainsString('meui-auth-aside', FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START)->toHtml());
    }

    #[Test]
    public function it_publishes_its_complete_stylesheet_through_filament(): void
    {
        $this->artisan('filament:assets')->assertSuccessful();

        foreach (['theme', 'customization'] as $name) {
            $asset = public_path('css/saivra/meui-theme/'.$name.'.css');
            self::assertFileExists($asset);
            self::assertSame(hash_file('sha256', __DIR__.'/../resources/css/'.$name.'.css'), hash_file('sha256', $asset));
        }
    }
}
