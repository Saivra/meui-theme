@props(['tone' => 'info', 'heading' => null, 'description' => null, 'icon' => null])
@php
    $tone = in_array($tone, ['info', 'success', 'warning', 'danger', 'announcement'], true) ? $tone : 'info';
    $icon ??= match ($tone) { 'danger' => 'heroicon-o-exclamation-circle', 'warning' => 'heroicon-o-exclamation-triangle', 'success' => 'heroicon-o-check-circle', 'announcement' => 'heroicon-o-megaphone', default => 'heroicon-o-information-circle' };
@endphp
<div {{ $attributes->class(['meui-banner'])->merge(['data-tone' => $tone, 'role' => in_array($tone, ['warning', 'danger'], true) ? 'alert' : 'status']) }}>
    <span class="meui-banner-icon" aria-hidden="true"><x-filament::icon :icon="$icon" /></span>
    <div class="meui-banner-copy">@if ($heading)<strong>{{ $heading }}</strong>@endif @if ($description)<span>{{ $description }}</span>@endif {{ $slot }}</div>
    @isset($actions)<div class="meui-banner-actions">{{ $actions }}</div>@endisset
    @isset($controls)<div class="meui-banner-controls">{{ $controls }}</div>@endisset
</div>
