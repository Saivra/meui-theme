<x-filament-panels::page>
    <x-meui::page-intro title="Components in context" description="Native fields, repeatable content, uploads and multi-step forms." />
    <form wire:submit="validateExample">
        {{ $this->form }}
        <x-filament::button type="submit">Validate example</x-filament::button>
    </form>
    {{ $this->reviewAction }}
    <x-filament-actions::modals />
</x-filament-panels::page>
