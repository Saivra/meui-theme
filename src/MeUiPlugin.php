<?php

namespace Saivra\MeUi;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Saivra\MeUi\Livewire\Sidebar;
use Saivra\MeUi\Livewire\Topbar;
use Saivra\MeUi\Pages\ComponentGallery;

class MeUiPlugin implements Plugin
{
    protected string $density = 'comfortable';

    protected ?Closure $galleryAuthorization = null;

    protected bool $hasAuthAside = true;

    protected bool $hasShell = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'meui';
    }

    public function density(string $density): static
    {
        if (! in_array($density, ['comfortable', 'compact'], true)) {
            throw new \InvalidArgumentException('MeUI density must be comfortable or compact.');
        }

        $this->density = $density;

        return $this;
    }

    public function getDensity(): string
    {
        return $this->density;
    }

    public function authAside(bool $condition = true): static
    {
        $this->hasAuthAside = $condition;

        return $this;
    }

    public function shell(bool $condition = true): static
    {
        $this->hasShell = $condition;

        return $this;
    }

    /** The gallery is opt-in and always needs the host's authorization policy. */
    public function gallery(Closure $authorize): static
    {
        $this->galleryAuthorization = $authorize;

        return $this;
    }

    public function canAccessGallery(): bool
    {
        return Filament::auth()->check() && $this->galleryAuthorization !== null && (bool) ($this->galleryAuthorization)();
    }

    public function register(Panel $panel): void
    {
        if ($this->galleryAuthorization !== null) {
            $panel->pages([ComponentGallery::class]);
        }

        if ($this->hasShell) {
            $panel->sidebarWidth('17.5rem')->collapsedSidebarWidth('5rem')
                ->sidebarCollapsibleOnDesktop()
                ->sidebarLivewireComponent(Sidebar::class)
                ->topbarLivewireComponent(Topbar::class);
        }

        $this->hook($panel, PanelsRenderHook::STYLES_AFTER, 'meui::hooks.styles');
        $this->hook($panel, PanelsRenderHook::BODY_START, 'meui::hooks.root');
        $this->hook($panel, PanelsRenderHook::TOPBAR_LOGO_AFTER, 'meui::hooks.context');

        if ($this->hasAuthAside) {
            $this->hook($panel, PanelsRenderHook::SIMPLE_LAYOUT_START, 'meui::hooks.auth-aside');
        }
    }

    protected function hook(Panel $panel, string $hook, string $view): void
    {
        $panelId = $panel->getId();

        $panel->renderHook($hook, fn (): View|string => Filament::getCurrentPanel()?->getId() === $panelId
            ? view($view, ['theme' => $this])
            : '');
    }

    public function boot(Panel $panel): void {}
}
