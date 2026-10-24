<?php

namespace Saivra\MeUi;

use Closure;
use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Saivra\MeUi\Livewire\Sidebar;
use Saivra\MeUi\Livewire\Topbar;
use Saivra\MeUi\Pages\ComponentGallery;
use Saivra\MeUi\Pages\ThemeSettings;
use Saivra\MeUi\Support\PreferenceStore;
use Saivra\MeUi\Support\ThemeOptions;

class MeUiPlugin implements Plugin
{
    /** @var array<string, string|Closure> */
    protected array $options = ThemeOptions::DEFAULTS;

    protected bool|Closure $customizerAuthorization = false;

    /** @var list<string> */
    protected array $customizableOptions = ['density', 'palette', 'accent', 'radius', 'surface', 'sidebar', 'workspace', 'contrast'];

    protected ?Closure $loadPreferences = null;

    protected ?Closure $savePreferences = null;

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

    public function density(string|Closure $density): static
    {
        return $this->option('density', $density);
    }

    public function getDensity(): string
    {
        return $this->getThemePreferences()['density'];
    }

    public function palette(string|Closure $palette): static
    {
        return $this->option('palette', $palette);
    }

    public function accent(string|Closure $accent): static
    {
        return $this->option('accent', $accent);
    }

    public function radius(string|Closure $radius): static
    {
        return $this->option('radius', $radius);
    }

    public function surface(string|Closure $surface): static
    {
        return $this->option('surface', $surface);
    }

    public function sidebarStyle(string|Closure $sidebar): static
    {
        return $this->option('sidebar', $sidebar);
    }

    public function workspace(string|Closure $workspace): static
    {
        return $this->option('workspace', $workspace);
    }

    public function contrast(string|Closure $contrast): static
    {
        return $this->option('contrast', $contrast);
    }

    protected function option(string $name, string|Closure $value): static
    {
        if (! $value instanceof Closure) {
            ThemeOptions::assertValid($name, $value);
        }

        $this->options[$name] = $value;

        return $this;
    }

    public function customizer(bool|Closure $authorize = true): static
    {
        $this->customizerAuthorization = $authorize;

        return $this;
    }

    /** @param list<string> $options */
    public function customizerOptions(array $options): static
    {
        if ($options === [] || array_diff($options, array_keys(ThemeOptions::CHOICES)) !== []) {
            throw new \InvalidArgumentException('MeUI customizer options must be supported theme option names.');
        }

        $this->customizableOptions = array_values(array_unique($options));

        return $this;
    }

    /** Supply database-backed persistence from the host application instead of MeUI's optional table. */
    public function preferences(Closure $load, Closure $save): static
    {
        $this->loadPreferences = $load;
        $this->savePreferences = $save;

        return $this;
    }

    public function canCustomize(): bool
    {
        return Filament::auth()->check() && (bool) ($this->customizerAuthorization instanceof Closure
            ? ($this->customizerAuthorization)()
            : $this->customizerAuthorization);
    }

    /** @return list<string> */
    public function getCustomizableOptions(): array
    {
        return $this->customizableOptions;
    }

    /** @return array<string, string> */
    public function getDefaultPreferences(): array
    {
        $defaults = [];

        foreach ($this->options as $name => $value) {
            $defaults[$name] = ThemeOptions::assertValid($name, $value instanceof Closure ? $value() : $value);
        }

        return $defaults;
    }

    /** @return array<string, string> */
    public function getThemePreferences(): array
    {
        $defaults = $this->getDefaultPreferences();

        if (! $this->canCustomize()) {
            return $defaults;
        }

        $stored = $this->loadPreferences !== null ? ($this->loadPreferences)() : app(PreferenceStore::class)->read();
        $stored = is_array($stored) ? ThemeOptions::sanitize($stored) : [];

        return array_replace($defaults, array_intersect_key($stored, array_flip($this->customizableOptions)));
    }

    /** @param array<string, mixed> $values */
    public function persistPreferences(array $values): void
    {
        abort_unless($this->canCustomize(), 403);

        foreach ($values as $name => $value) {
            if (! in_array($name, $this->customizableOptions, true) || ! is_string($value)
                || ! in_array($value, ThemeOptions::CHOICES[$name] ?? [], true)) {
                throw ValidationException::withMessages(['data.'.$name => __('meui::ui.customizer.invalid')]);
            }
        }

        if ($this->savePreferences !== null) {
            ($this->savePreferences)($values);
        } else {
            app(PreferenceStore::class)->write($values);
        }
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

        if ($this->customizerAuthorization !== false) {
            $panel->pages([ThemeSettings::class])->userMenuItems([
                Action::make('meui-theme')->label(fn (): string => __('meui::ui.customizer.menu'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->url(fn (): string => ThemeSettings::getUrl())
                    ->visible(fn (): bool => $this->canCustomize()),
            ]);
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
