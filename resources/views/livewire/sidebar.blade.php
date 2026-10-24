@php
    use Filament\Enums\DatabaseNotificationsPosition;
    use Filament\Enums\GlobalSearchPosition;
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;

    $navigation = filament()->getNavigation();
    $collapsedGroups = collect($navigation)->filter(fn ($group) => $group->isCollapsed())->map(function ($group) {
        $label = $group->getLabel();
        return $label instanceof \Illuminate\Contracts\Support\Htmlable ? $label->toHtml() : $label;
    })->values()->all();
@endphp
<div class="meui-sidebar-component">
    <aside id="fi-main-sidebar" class="fi-sidebar fi-main-sidebar meui-sidebar" x-data="{}" x-init="$store.sidebar.collapsedGroups ??= @js($collapsedGroups)" x-cloak x-bind:class="{ 'fi-sidebar-open': $store.sidebar.isOpen }">
        {{ FilamentView::renderHook(PanelsRenderHook::SIDEBAR_START) }}
        <div class="meui-sidebar-brand">
            {{ FilamentView::renderHook(PanelsRenderHook::SIDEBAR_LOGO_BEFORE) }}
            <a {{ \Filament\Support\generate_href_html(filament()->getHomeUrl() ?? filament()->getUrl()) }} class="meui-brand-link">
                <span class="meui-brand-symbol" aria-hidden="true"><x-filament::icon icon="heroicon-o-square-3-stack-3d" /></span>
                <span x-show="$store.sidebar.isOpen" class="meui-brand-name"><x-filament-panels::logo /></span>
            </a>
            <x-filament::icon-button color="gray" icon="heroicon-o-x-mark" :label="__('filament-panels::layout.actions.sidebar.collapse.label')" class="meui-sidebar-mobile-close" x-on:click="$store.sidebar.close()" />
            {{ FilamentView::renderHook(PanelsRenderHook::SIDEBAR_LOGO_AFTER) }}
        </div>
        @if (filament()->hasTenancy() && filament()->getTenant() && filament()->hasTenantMenu())
            <div class="meui-sidebar-workspace"><x-filament-panels::tenant-menu /></div>
        @else
            <div class="meui-sidebar-caption" x-show="$store.sidebar.isOpen">{{ __('meui::ui.navigation') }}</div>
        @endif
        @if (filament()->isGlobalSearchEnabled() && filament()->getGlobalSearchPosition() === GlobalSearchPosition::Sidebar)
            <div class="meui-sidebar-search" x-show="$store.sidebar.isOpen">@livewire(\Filament\Livewire\GlobalSearch::class)</div>
        @endif
        <nav class="fi-sidebar-nav" aria-label="{{ __('filament-panels::layout.navigation.label') }}">
            {{ FilamentView::renderHook(PanelsRenderHook::SIDEBAR_NAV_START) }}
            <ul class="fi-sidebar-nav-groups">
                @foreach ($navigation as $group)
                    <x-filament-panels::sidebar.group :active="$group->isActive()" :collapsible="$group->isCollapsible()" :icon="$group->getIcon()" :items="$group->getItems()" :label="$group->getLabel()" :attributes="\Filament\Support\prepare_inherited_attributes($group->getExtraSidebarAttributeBag())" />
                @endforeach
            </ul>
            {{ FilamentView::renderHook(PanelsRenderHook::SIDEBAR_NAV_END) }}
        </nav>
        @if (filament()->auth()->check())
            <div class="meui-sidebar-account">
                @if (filament()->hasDatabaseNotifications() && filament()->getDatabaseNotificationsPosition() === DatabaseNotificationsPosition::Sidebar)
                    @livewire(filament()->getDatabaseNotificationsLivewireComponent(), ['lazy' => filament()->hasLazyLoadedDatabaseNotifications()])
                @endif
                @if (filament()->hasUserMenu())<x-meui::profile-menu />@endif
            </div>
        @endif
        {{ FilamentView::renderHook(PanelsRenderHook::SIDEBAR_FOOTER) }}
    </aside>
    <x-filament-actions::modals />
</div>
