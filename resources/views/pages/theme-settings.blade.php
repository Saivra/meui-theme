<x-filament-panels::page>
    <div class="meui-customizer-layout">
        <form wire:submit="save" class="meui-customizer-form">
            {{ $this->form }}
            <div class="meui-customizer-actions">
                <x-filament::button type="submit" wire:loading.attr="disabled" wire:target="save,resetPreferences" icon="heroicon-o-check">{{ __('meui::ui.customizer.save') }}</x-filament::button>
                <x-filament::button type="button" color="gray" wire:click="resetPreferences" wire:loading.attr="disabled" wire:target="save,resetPreferences">{{ __('meui::ui.customizer.reset') }}</x-filament::button>
            </div>
        </form>
        @php
            $previewAttributes = new \Illuminate\View\ComponentAttributeBag;
            foreach ($this->previewPreferences() as $name => $value) {
                $previewAttributes = $previewAttributes->merge(['data-'.$name => $value]);
            }
        @endphp
        <section class="meui-preferences-preview" {{ $previewAttributes }} aria-label="{{ __('meui::ui.customizer.preview') }}">
            <div class="meui-preview-caption"><span>{{ __('meui::ui.customizer.preview') }}</span><span>{{ __('meui::ui.customizer.example') }}</span></div>
            <div class="meui-preview-app">
                <aside class="meui-preview-sidebar" aria-hidden="true">
                    <span class="meui-preview-brand">m<span>MeUI</span></span>
                    <span class="meui-preview-nav meui-preview-nav-active"><x-filament::icon icon="heroicon-o-squares-2x2" /><span>{{ __('meui::ui.customizer.sample.overview') }}</span></span>
                    <span class="meui-preview-nav"><x-filament::icon icon="heroicon-o-shopping-bag" /><span>{{ __('meui::ui.customizer.sample.orders') }}</span></span>
                    <span class="meui-preview-nav"><x-filament::icon icon="heroicon-o-users" /><span>{{ __('meui::ui.customizer.sample.people') }}</span></span>
                </aside>
                <div class="meui-preview-workspace">
                    <div class="meui-preview-heading"><span>{{ __('meui::ui.customizer.sample.overview') }}</span><x-filament::badge color="success">{{ __('meui::ui.customizer.sample.live') }}</x-filament::badge></div>
                    <div class="meui-preview-stats">
                        <div><span>{{ __('meui::ui.customizer.sample.revenue') }}</span><strong>$24,680</strong><small>+12.8%</small><svg viewBox="0 -16 200 64" aria-hidden="true"><path d="M0 35 Q20 10 40 22 T80 20 T120 12 T160 8 T200 2" /></svg></div>
                        <div><span>{{ __('meui::ui.customizer.sample.orders') }}</span><strong>1,284</strong><small>+8.2%</small><svg viewBox="0 -16 200 64" aria-hidden="true"><path d="M0 36 Q20 20 40 28 T80 15 T120 20 T160 10 T200 4" /></svg></div>
                    </div>
                    <div class="meui-preview-card">
                        <div class="meui-preview-card-heading">{{ __('meui::ui.customizer.sample.activity') }}</div>
                        <table><caption class="fi-sr-only">{{ __('meui::ui.customizer.sample.activity') }}</caption><thead><tr><th>{{ __('meui::ui.customizer.sample.customer') }}</th><th>{{ __('meui::ui.customizer.sample.status') }}</th><th>{{ __('meui::ui.customizer.sample.amount') }}</th></tr></thead><tbody>
                            <tr><td>Olivia Park</td><td><x-filament::badge color="success">{{ __('meui::ui.customizer.sample.paid') }}</x-filament::badge></td><td>$210.00</td></tr>
                            <tr><td>James Foster</td><td><x-filament::badge color="warning">{{ __('meui::ui.customizer.sample.pending') }}</x-filament::badge></td><td>$396.00</td></tr>
                            <tr><td>Mia Thompson</td><td><x-filament::badge color="success">{{ __('meui::ui.customizer.sample.paid') }}</x-filament::badge></td><td>$216.00</td></tr>
                        </tbody></table>
                    </div>
                    <div class="meui-preview-footer"><span>{{ __('meui::ui.customizer.sample.updated') }}</span><x-filament::button size="sm" disabled>{{ __('meui::ui.customizer.sample.view') }}</x-filament::button></div>
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>
