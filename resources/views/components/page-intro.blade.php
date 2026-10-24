@props(['eyebrow' => null, 'title', 'description' => null])
<header {{ $attributes->class(['meui-page-intro']) }}>
    <div>
        @if ($eyebrow)<p class="meui-eyebrow">{{ $eyebrow }}</p>@endif
        <h2>{{ $title }}</h2>
        @if ($description)<p class="meui-description">{{ $description }}</p>@endif
    </div>
    @isset($actions)<div class="meui-intro-actions">{{ $actions }}</div>@endisset
</header>
