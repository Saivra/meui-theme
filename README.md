# MeUI for Filament

A native application theme for Filament 5, maintained by [Saivra Technologies](https://github.com/saivra).
MeUI gives your panel a full-height navigation rail, an inset workspace, clear light and dark surfaces,
and a consistent treatment of tables, forms, records, actions and notices.

[![Tests](https://github.com/saivra/meui-theme/actions/workflows/tests.yml/badge.svg)](https://github.com/saivra/meui-theme/actions/workflows/tests.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

MeUI uses Filament, Livewire and ordinary CSS. It has no JavaScript framework, external font, tracking service,
licence server, database migration or application model of its own.

![MeUI desktop light theme](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/desktop-light.jpg)

Screenshots use the standalone consumer with fictitious data:
[dark workspace](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/desktop-dark.jpg),
[dark form](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/forms-dark.jpg),
[mobile form](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/mobile-dark.jpg).

## Requirements

| Dependency | Supported versions |
| --- | --- |
| PHP | 8.2 or later; Laravel 13 requires PHP 8.3 or later |
| Laravel | 12 and 13 |
| Filament | 5.10.2 or later within 5.x |
| Browser | Current Chrome, Edge, Firefox or Safari with CSS nesting, `:has()` and `color-mix()` support |

The test matrix covers Laravel 12 and 13, the minimum supported and current Filament versions, and SQLite and
PostgreSQL. See [CI](https://github.com/saivra/meui-theme/actions/workflows/tests.yml) for the latest results.

## Installation

```shell
composer require saivra/meui-theme
php artisan filament:assets
```

Register the plugin in each panel you want to theme:

```php
use Saivra\MeUi\MeUiPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // Your panel's existing configuration...
        ->plugin(MeUiPlugin::make());
}
```

Laravel discovers the service provider automatically. MeUI augments Filament's standard theme, so a standard
panel needs no Node build. Run `php artisan filament:assets` after package upgrades and during deployment.
Clear cached views with `php artisan view:clear` if you see stale markup after an upgrade.

To install directly from the public GitHub repository before Packagist indexing is available:

```shell
composer config repositories.meui vcs https://github.com/saivra/meui-theme
composer require saivra/meui-theme:0.1.0
```

### Existing custom themes

Keep your Vite theme if your application or other plugins need its utility classes. Add MeUI's views to its
Tailwind source discovery, adjusting the relative path for your CSS file:

```css
/* resources/css/filament/admin/theme.css */
@source '../../../../vendor/saivra/meui-theme/resources/views/**/*.blade.php';
```

Rebuild that theme with your normal asset command. Remove competing theme engines before enabling MeUI;
multiple themes styling the same Filament hooks can conflict.

## Options

```php
MeUiPlugin::make()
    ->density('compact')  // 'comfortable' by default
    ->authAside(false)    // hide the split authentication aside
    ->shell(false);      // retain your existing sidebar, topbar and widths
```

The full shell is enabled by default. It provides native navigation, tenant switching, global search,
notifications and a profile menu with Filament's light/dark/system controls. Top-navigation panels use
Filament's native topbar inside the notice rail. `shell(false)` keeps your panel's original shell components
and dimensions while applying component styling.

Brand names, logos, colors, menus, permissions, guards, tenancy, MFA and authentication remain configured
by your application. MeUI does not replace them. Persist user-facing options through your application's
own settings system. The component studio's density selector is a temporary preview.

The authentication aside also appears on native registration, recovery, verification and MFA pages. Disable
it for a panel with a custom simple-page layout. Shell and authentication copy use namespaced translations;
override them in `lang/vendor/meui/en/ui.php`. The included studio examples are in English.

## Component studio

The studio is off by default. Enable it only for an intended audience, with an authorization closure:

```php
MeUiPlugin::make()->gallery(
    fn (): bool => auth()->user()?->can('viewComponentStudio') ?? false,
);
```

Define that ability in your application. An authenticated user and a successful authorization check are
required on entry and interactive actions. The page appears at your panel's `meui-studio` route. For a local
reference screen, register it only when `app()->isLocal()`.

Explore real table search, filters, sorting and empty results; form validation and reset; record details;
dialogs; banner previews; theme and density controls. All records are fictitious. The studio does not query
or save your application's business records, send invitations or collect sign-in credentials.

## Application notices

The full shell provides three notice rails:

```php
use Saivra\MeUi\MeUiRenderHook;

$panel
    ->renderHook(MeUiRenderHook::SECURITY_BANNERS, fn () => view('notices.identity'))
    ->renderHook(MeUiRenderHook::BANNERS, fn () => view('notices.account'))
    ->renderHook(MeUiRenderHook::BANNERS_AFTER, fn () => view('notices.announcement'));
```

Security notices appear above the utility bar. Primary and secondary notices follow below it. The shell
measures the complete header so wrapped mobile notices leave enough room for navigation and focused content.
Your application decides visibility, urgency, links and dismissal. With `shell(false)`, place notices through
your existing layout and Filament hooks instead.

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

Tones are `info`, `success`, `warning`, `danger` and `announcement`. Warning and danger use `role="alert"`;
other tones use `status`. You can override the role. Components accept normal Blade attributes, escaped
heading/description values, default content and optional `actions` and `controls` slots. MeUI never
automatically dismisses a critical notice.

## Layout components

```blade
<x-meui::page-intro
    eyebrow="Workspace"
    title="Team settings"
    description="Keep your team's essentials up to date."
>
    <x-slot name="actions">{{ $this->saveAction }}</x-slot>
</x-meui::page-intro>

<x-meui::frame title="People" description="Everyone with a shared view.">
    {{ $this->table }}
    <x-slot name="footer">{{ $memberCount }} people</x-slot>
</x-meui::frame>

<x-meui::empty-state
    title="No results"
    description="Try another search."
    icon="heroicon-o-magnifying-glass"
/>
```

## Customization and accessibility

MeUI uses Filament's primary and semantic colors. Surface variables include `--meui-canvas`,
`--meui-surface`, `--meui-subtle`, `--meui-hover`, `--meui-border`, `--meui-ink`, `--meui-muted`,
`--meui-accent`, `--meui-radius` and `--meui-control-radius`.

Styles are scoped to panels that enable MeUI. Native keyboard and modal behavior remain in Filament;
MeUI includes visible focus treatments, semantic notice roles, reduced-motion rules, logical spacing
and print styles. Custom brand colors, content, additional plugins and overridden layouts still need
to be checked in your application. See [compatibility](docs/compatibility.md) for tested scope and limits.

## Development

```shell
composer install
composer test
composer analyse
php vendor/bin/pint --test
```

The package has its own Testbench consumer and test fixtures; no host application is needed. For the
PostgreSQL lane, provide the `MEUI_DB_*` environment variables described in [CONTRIBUTING.md](CONTRIBUTING.md).
The fixtures cover native repeaters, a builder, rich editor, upload field, wizard and slide-over.
They are test resources and are excluded from Composer distribution archives.

## Support and releases

Report reproducible bugs through [GitHub issues](https://github.com/saivra/meui-theme/issues).
Include your PHP, Laravel, Filament and MeUI versions, browser and the smallest reproduction you can share.
Keep credentials and customer information out of reports. See [SECURITY.md](SECURITY.md) for private reporting.

MeUI is in the `0.x` series. Minor releases may change public APIs; patch releases are intended for
compatible fixes. Check the [changelog](CHANGELOG.md) before upgrading and test your application's panels.

Created and maintained by [Ghulam Mustafa](https://github.com/GMustafa-s) at
[Saivra Technologies](https://github.com/saivra). Released under the [MIT license](LICENSE).
