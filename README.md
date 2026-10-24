# MeUI for Filament

A considered workspace for your Filament application. MeUI brings a full-height navigation rail, an inset
content area and calm, consistent surfaces to the components you already use.

Built for **Filament 5**. Free and open source, under the **MIT license**.

[![Tests](https://github.com/saivra/meui-theme/actions/workflows/tests.yml/badge.svg)](https://github.com/saivra/meui-theme/actions/workflows/tests.yml)
[![Packagist](https://img.shields.io/packagist/v/saivra/meui-theme)](https://packagist.org/packages/saivra/meui-theme)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/saivra/meui-theme/blob/main/LICENSE)

![MeUI marketing preview in light and dark mode](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/meui-listing.jpg)

Marketing artwork shows an enhanced visual direction based on the
[official Filament demo](https://github.com/filamentphp/demo). The original application captures below
show the current theme with the same seeded demonstration data. MeUI is an independent community theme
by [Saivra Technologies](https://github.com/saivra).

## Light and dark, side by side

The illustrated comparisons pair the original demo with MeUI's marketing preview. The preview includes
a richer ApexCharts-style chart composition; MeUI itself styles the host application's components and
does not install or replace its chart library.

![Original Filament demo and MeUI marketing preview, light mode](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/comparison-light.jpg)

![Original Filament demo and MeUI marketing preview, dark mode](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/comparison-dark.jpg)

### Current application captures

These are unretouched browser captures of the same official demo dashboard, using the same data and
1600×1000 CSS-pixel viewport. Both installations use Filament 5.10.3. The original retains the demo's
standard layout; the themed installation enables MeUI's shell and topbar.

| Original Filament demo | MeUI applied |
| --- | --- |
| ![Original Filament demo, light](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/original-dashboard-light.jpg) | ![Current MeUI demo, light](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/shop-dashboard-light.jpg) |
| ![Original Filament demo, dark](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/original-dashboard-dark.jpg) | ![Current MeUI demo, dark](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/shop-dashboard-dark.jpg) |

### Directory thumbnail

![MeUI directory thumbnail](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/meui-thumbnail.jpg)

[Main image: 2560×1440 JPEG](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/meui-listing.jpg)
· [Thumbnail: 1280×720 JPEG](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/meui-thumbnail.jpg)

## A complete application surface

- **One coherent workspace.** Navigation, global search, tenant switching, notifications and the profile menu.
- **Light, dark and system appearance.** Designed surfaces for tables, charts, forms, records and dialogs.
- **Native Filament components.** Your resources, actions, validation and authorization continue to work through Filament.
- **Room for your brand.** Keep your panel name, logo, primary color, navigation and authentication configuration.
- **Practical layout options.** Comfortable or compact density, optional shell, and a split authentication layout.
- **Clear notices.** Dedicated header rails and five semantic banner tones for application messages.

MeUI uses Filament, Livewire and ordinary CSS. It adds no database migrations, application models,
tracking, external fonts or license service.

## Installation

```shell
composer require saivra/meui-theme
php artisan filament:assets
```

Register MeUI in your panel provider:

```php
use Saivra\MeUi\MeUiPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // Your existing panel configuration...
        ->plugin(MeUiPlugin::make());
}
```

Repeat this for each panel you want to theme. Laravel discovers the service provider automatically.
A standard Filament panel needs no Node build. If you already use a Vite theme, keep it and follow the
[custom-theme setup](https://github.com/saivra/meui-theme/blob/main/docs/installation.md#existing-vite-themes).

Run `php artisan filament:assets` after package upgrades and during deployment.

## Requirements

| Dependency | Supported versions |
| --- | --- |
| PHP | 8.2+ on Laravel 12; 8.3+ on Laravel 13 |
| Laravel | 12 and 13 |
| Filament | 5.10.2 or later within 5.x |
| Browser | Current Chrome, Edge, Firefox or Safari with modern CSS support |

The [compatibility matrix](https://github.com/saivra/meui-theme/actions/workflows/tests.yml) tests
Laravel 12 and 13, minimum and current Filament versions, and SQLite and PostgreSQL.
See [tested scope and limitations](https://github.com/saivra/meui-theme/blob/main/docs/compatibility.md).

## See the theme across the app

| Orders in light mode | Employees in dark mode |
| --- | --- |
| ![Orders with MeUI](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/orders-light.jpg) | ![Employees with MeUI](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/employees-dark.jpg) |

The [screenshot gallery](https://github.com/saivra/meui-theme/blob/main/docs/screenshots.md) continues
through product editing and project management. Application captures come from the official demo;
illustrated marketing previews are labeled separately.

## Configuration

```php
MeUiPlugin::make()
    ->density('compact')
    ->authAside(false)
    ->shell(false);
```

| Option | Default | Effect |
| --- | --- | --- |
| `density('comfortable' \| 'compact')` | `comfortable` | Adjust the component density. |
| `authAside(bool)` | `true` | Show the split authentication aside on native simple pages. |
| `shell(bool)` | `true` | Use MeUI's sidebar, topbar and widths. Disable it to retain your panel's shell. |
| `gallery(Closure)` | Disabled | Enable the component studio for authenticated users who pass your authorization closure. |

Branding, colors, menus, guards, tenancy, MFA and permissions are configured through your application.
See [customization](https://github.com/saivra/meui-theme/blob/main/docs/customization.md) for examples,
translations and CSS variables.

## Documentation

| Guide | What it covers |
| --- | --- |
| [Installation](https://github.com/saivra/meui-theme/blob/main/docs/installation.md) | Setup, existing Vite themes, deployment, upgrades and troubleshooting. |
| [Customization](https://github.com/saivra/meui-theme/blob/main/docs/customization.md) | Branding, appearance, density, shell, translations and styling. |
| [Components and notices](https://github.com/saivra/meui-theme/blob/main/docs/components.md) | Banner rails, Blade components and the authorized component studio. |
| [Screenshot gallery](https://github.com/saivra/meui-theme/blob/main/docs/screenshots.md) | The official Filament demo with MeUI, across the application. |
| [Compatibility](https://github.com/saivra/meui-theme/blob/main/docs/compatibility.md) | Supported versions, validation scope and integration limits. |

## Contributing and support

The package has an independent Testbench consumer. Run `composer test`, `composer analyse` and
`php vendor/bin/pint --test` after `composer install`.
[CONTRIBUTING.md](https://github.com/saivra/meui-theme/blob/main/CONTRIBUTING.md) covers the PostgreSQL lane
and contribution workflow.

Open a [GitHub issue](https://github.com/saivra/meui-theme/issues) with your versions, browser and a small
reproduction. Report vulnerabilities through the private process in
[SECURITY.md](https://github.com/saivra/meui-theme/blob/main/SECURITY.md).

MeUI is in the `0.x` series. Minor releases may change public APIs; patch releases are intended for
compatible fixes. Review the [changelog](https://github.com/saivra/meui-theme/blob/main/CHANGELOG.md)
and test your panels before upgrading.

Created and maintained by [Ghulam Mustafa](https://github.com/GMustafa-s) at
[Saivra Technologies](https://github.com/saivra). Released under the
[MIT license](https://github.com/saivra/meui-theme/blob/main/LICENSE).
