@props(['compact' => false])
@php
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;

    $user = filament()->auth()->user();
    $userName = filament()->getUserName($user);
    $items = collect($this->getUserMenuItems());
    $before = $items->filter(fn ($item) => $item->getSort() < 0)
        ->reject(fn ($item) => $item->getName() === 'profile' && blank($item->getUrl()) && ! $item->hasAction());
    $after = $items->filter(fn ($item) => $item->getSort() >= 0);
@endphp
{{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_BEFORE) }}
<x-filament::dropdown placement="{{ $compact ? 'bottom-end' : 'top-end' }}" width="xs" teleport :attributes="$attributes->class(['meui-profile-menu', 'meui-profile-menu-compact' => $compact])">
    <x-slot name="trigger">
        <button type="button" class="meui-profile-card" aria-label="{{ __('filament-panels::layout.actions.open_user_menu.label') }}">
            <x-filament-panels::avatar.user :user="$user" loading="lazy" />
            @if (! $compact)
                <span class="meui-profile-card-text" x-show="$store.sidebar.isOpen"><strong>{{ $userName }}</strong><span>{{ __('meui::ui.account_preferences') }}</span></span>
                <x-filament::icon icon="heroicon-o-chevron-up-down" class="meui-profile-chevron" x-show="$store.sidebar.isOpen" />
            @endif
        </button>
    </x-slot>
    <div class="meui-profile-menu-heading"><span class="meui-eyebrow">{{ __('meui::ui.signed_in') }}</span><strong>{{ $userName }}</strong></div>
    @if ($before->isNotEmpty())
        <x-filament::dropdown.list>@foreach ($before as $item){{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}{{ $item }}{{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}@endforeach</x-filament::dropdown.list>
    @endif
    @if (filament()->hasDarkMode() && ! filament()->hasDarkModeForced() && filament()->hasThemeSwitcher())
        <div class="meui-profile-appearance"><span>{{ __('meui::ui.appearance') }}</span><x-filament-panels::theme-switcher /></div>
    @endif
    @if ($this->hasMultipleUserMenuItemGroups())
        @foreach ($this->getUserMenuItemGroupsAfterTheme() as $group)
            <x-filament::dropdown.list>@foreach ($group as $item){{ $item }}@endforeach</x-filament::dropdown.list>
        @endforeach
    @elseif ($after->isNotEmpty())
        <x-filament::dropdown.list>@foreach ($after as $item){{ $item }}@endforeach</x-filament::dropdown.list>
    @endif
</x-filament::dropdown>
{{ FilamentView::renderHook(PanelsRenderHook::USER_MENU_AFTER) }}
