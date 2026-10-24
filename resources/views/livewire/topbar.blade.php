@php
    use Filament\Enums\DatabaseNotificationsPosition;
    use Filament\Enums\GlobalSearchPosition;
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;
    use Saivra\MeUi\MeUiRenderHook;
@endphp
<div class="meui-header-shell" x-data="{ observer: null, init() { this.observer = new ResizeObserver(() => document.documentElement.style.setProperty('--meui-header-height', Math.ceil(this.$el.getBoundingClientRect().height) + 'px')); this.observer.observe(this.$el) }, destroy() { this.observer?.disconnect(); document.documentElement.style.removeProperty('--meui-header-height') } }">
    <div class="meui-security-banners">{{ FilamentView::renderHook(MeUiRenderHook::SECURITY_BANNERS) }}</div>
    @if (filament()->hasTopNavigation())
        @include('filament-panels::livewire.topbar')
    @else
        <div class="fi-topbar-ctn meui-topbar-ctn">
            <nav class="fi-topbar meui-topbar" aria-label="{{ __('filament-panels::layout.topbar.label') }}">
                {{ FilamentView::renderHook(PanelsRenderHook::TOPBAR_START) }}
                @if (filament()->hasNavigation())
                    <x-filament::icon-button color="gray" icon="heroicon-o-bars-3-bottom-left" :label="__('meui::ui.toggle_navigation')" class="meui-sidebar-toggle" aria-controls="fi-main-sidebar" x-bind:aria-expanded="$store.sidebar.isOpen" x-on:click="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()" />
                @endif
                <div class="meui-topbar-location">
                    {{ FilamentView::renderHook(PanelsRenderHook::TOPBAR_LOGO_BEFORE) }}
                    <span class="meui-topbar-workspace">{{ filament()->getTenant() ? filament()->getTenantName(filament()->getTenant()) : __('meui::ui.workspace') }}</span>
                    <span class="meui-context-divider" aria-hidden="true"></span>
                    <strong>{{ $this->contextLabel() }}</strong>
                    {{ FilamentView::renderHook(PanelsRenderHook::TOPBAR_LOGO_AFTER) }}
                </div>
                <div class="fi-topbar-end meui-topbar-tools">
                    {{ FilamentView::renderHook(PanelsRenderHook::GLOBAL_SEARCH_BEFORE) }}
                    @if (filament()->isGlobalSearchEnabled() && filament()->getGlobalSearchPosition() === GlobalSearchPosition::Topbar)
                        @livewire(\Filament\Livewire\GlobalSearch::class)
                    @endif
                    {{ FilamentView::renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER) }}
                    @if (filament()->auth()->check())
                        @if (filament()->hasDatabaseNotifications() && filament()->getDatabaseNotificationsPosition() === DatabaseNotificationsPosition::Topbar)
                            <div class="meui-topbar-notifications">@livewire(filament()->getDatabaseNotificationsLivewireComponent(), ['lazy' => filament()->hasLazyLoadedDatabaseNotifications()])</div>
                        @endif
                        @if (filament()->hasUserMenu())<x-meui::profile-menu compact :class="filament()->hasNavigation() ? 'meui-mobile-profile' : ''" />@endif
                    @endif
                </div>
                {{ FilamentView::renderHook(PanelsRenderHook::TOPBAR_END) }}
            </nav>
        </div>
    @endif
    <section class="meui-banner-region" aria-label="{{ __('meui::ui.workspace_notices') }}">
        {{ FilamentView::renderHook(MeUiRenderHook::BANNERS) }}
        @if ($previewTone)
            <x-meui::banner :tone="$previewTone" heading="Component studio preview" description="This example stays above the workspace. Your application notices appear in this same rail.">
                <x-slot name="controls"><x-filament::icon-button icon="heroicon-o-x-mark" color="gray" label="Dismiss preview banner" wire:click="clearPreviewBanner" /></x-slot>
            </x-meui::banner>
        @endif
        {{ FilamentView::renderHook(MeUiRenderHook::BANNERS_AFTER) }}
    </section>
    <x-filament-actions::modals />
</div>
