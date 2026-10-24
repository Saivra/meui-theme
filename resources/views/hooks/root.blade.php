@php
    $preferences = $theme->getThemePreferences();
    $rootAttributes = new \Illuminate\View\ComponentAttributeBag;
    foreach ($preferences as $name => $value) {
        $rootAttributes = $rootAttributes->merge(['data-'.$name => $value, 'x-bind:data-'.$name => 'preferences.'.$name]);
    }
@endphp
<span hidden class="meui-root" {{ $rootAttributes }} x-data="{ preferences: @js($preferences) }" x-on:meui-theme-updated.window="if ($event.detail.panel === @js(filament()->getId())) preferences = $event.detail.preferences"></span>
