<?php

namespace Saivra\MeUi\Livewire;

use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Saivra\MeUi\MeUiPlugin;
use Saivra\MeUi\Pages\ThemeSettings;

class Topbar extends \Filament\Livewire\Topbar
{
    #[Locked]
    public ?string $previewTone = null;

    #[On('meui-preview-banner')]
    public function previewBanner(string $tone): void
    {
        $this->authorizeStudio();
        abort_unless(in_array($tone, ['info', 'success', 'warning', 'danger', 'announcement'], true), 404);
        $this->previewTone = $tone;
    }

    public function clearPreviewBanner(): void
    {
        $this->authorizeStudio();
        $this->previewTone = null;
    }

    protected function authorizeStudio(): void
    {
        $panel = Filament::getCurrentPanel();
        $plugin = $panel?->hasPlugin('meui') ? $panel->getPlugin('meui') : null;
        abort_unless($plugin instanceof MeUiPlugin && $plugin->canAccessGallery(), 403);
    }

    public function render(): View
    {
        return view('meui::livewire.topbar');
    }

    public function contextLabel(): string|Htmlable
    {
        if (request()->routeIs(ThemeSettings::getRouteName())) {
            return __('meui::ui.customizer.title');
        }

        foreach (Filament::getNavigation() as $group) {
            foreach ($group->getItems() as $item) {
                foreach ($item->getChildItems() as $child) {
                    if ($child->isActive()) {
                        return $child->getLabel();
                    }
                }

                if ($item->isActive()) {
                    return $item->getLabel();
                }
            }
        }

        return Filament::getBrandName();
    }
}
