@props(['title', 'description' => null, 'icon' => 'heroicon-o-inbox'])
<div {{ $attributes->class(['meui-empty']) }}>
    <x-filament::icon :icon="$icon" class="meui-empty-icon" />
    <h3>{{ $title }}</h3>
    @if ($description)<p>{{ $description }}</p>@endif
    {{ $slot }}
</div>
