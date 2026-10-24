@props(['title' => null, 'description' => null])
<section {{ $attributes->class(['meui-frame']) }}>
    @if ($title)
        <header class="meui-frame-header">
            <div><h3>{{ $title }}</h3>@if ($description)<p>{{ $description }}</p>@endif</div>
            @isset($actions)<div>{{ $actions }}</div>@endisset
        </header>
    @endif
    <div class="meui-frame-content">{{ $slot }}</div>
    @isset($footer)<footer class="meui-frame-footer">{{ $footer }}</footer>@endisset
</section>
