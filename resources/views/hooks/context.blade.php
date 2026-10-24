<span class="meui-context" aria-hidden="true">
    <span class="meui-context-divider"></span>
    {{ filament()->getTenant() ? filament()->getTenantName(filament()->getTenant()) : __('meui::ui.workspace') }}
</span>
