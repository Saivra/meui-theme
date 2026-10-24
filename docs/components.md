# Components and notices

[Back to the README](../README.md) · [Customization](customization.md)

Use native Filament resources, forms, infolists, tables, actions and notifications as usual. MeUI styles
those components and adds a small set of Blade helpers for application composition.

## Header notice rails

The full shell exposes three render hooks:

```php
use Saivra\MeUi\MeUiRenderHook;

$panel
    ->renderHook(MeUiRenderHook::SECURITY_BANNERS, fn () => view('notices.identity'))
    ->renderHook(MeUiRenderHook::BANNERS, fn () => view('notices.account'))
    ->renderHook(MeUiRenderHook::BANNERS_AFTER, fn () => view('notices.announcement'));
```

Security notices sit above the utility bar. Primary and secondary notices follow below it. The shell
measures the complete header so wrapped notices leave room for navigation and focused content on mobile.

Your application owns visibility, urgency, destination URLs and dismissal. MeUI never automatically
dismisses a critical notice. With `shell(false)`, place notices through your existing layout and Filament hooks.

## Banner

```blade
<x-meui::banner
    tone="warning"
    heading="Review your details"
    description="Check the contact information before continuing."
>
    <x-slot name="actions">
        <x-filament::button tag="a" :href="$reviewUrl">Review details</x-filament::button>
    </x-slot>
</x-meui::banner>
```

| Tone | Default role |
| --- | --- |
| `info` | `status` |
| `success` | `status` |
| `warning` | `alert` |
| `danger` | `alert` |
| `announcement` | `status` |

You can override `role`. Banners accept ordinary Blade attributes, escaped heading and description
values, default slot content, and optional `actions` and `controls` slots. Use `controls` for your own
dismissal action when dismissal is appropriate.

## Page introduction

```blade
<x-meui::page-intro
    eyebrow="Workspace"
    title="Team settings"
    description="Keep your team's essentials up to date."
>
    <x-slot name="actions">{{ $this->saveAction }}</x-slot>
</x-meui::page-intro>
```

## Content frame

```blade
<x-meui::frame title="People" description="Everyone with a shared view.">
    {{ $this->table }}
    <x-slot name="footer">{{ $memberCount }} people</x-slot>
</x-meui::frame>
```

## Empty state

```blade
<x-meui::empty-state
    title="No results"
    description="Try another search."
    icon="heroicon-o-magnifying-glass"
/>
```

## Authorized component studio

The studio is disabled by default. Enable it with an authorization closure for the intended audience:

```php
MeUiPlugin::make()->gallery(
    fn (): bool => auth()->user()?->can('viewComponentStudio') ?? false,
);
```

Define the ability in your application. Both an authenticated user and a successful authorization check
are required for entry and interactive actions. The page appears at the panel's `meui-studio` route.
For a local reference, register the gallery only when `app()->isLocal()`.

The studio demonstrates table search, filters, sorting and empty results; form validation and reset;
record details; dialogs; banner previews; appearance and density controls. Its records are fictitious.
It does not query or save your business records, send invitations or collect sign-in credentials.

The development test consumer also exercises native repeaters, a builder, rich editor, upload field,
wizard and slide-over. These fixtures are excluded from Composer distribution archives.
