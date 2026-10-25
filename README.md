# MeUI for Filament

A polished workspace for Filament 5, with refined light and dark surfaces, full-height navigation,
an inset content area and personal appearance controls. Free and open source under the MIT license.

[![Tests](https://github.com/saivra/meui-theme/actions/workflows/tests.yml/badge.svg)](https://github.com/saivra/meui-theme/actions/workflows/tests.yml)
[![Packagist](https://img.shields.io/packagist/v/saivra/meui-theme)](https://packagist.org/packages/saivra/meui-theme)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/saivra/meui-theme/blob/main/LICENSE)

<img class="filament-hidden" src="https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/meui-listing.jpg" alt="MeUI light and dark marketing preview" />

The hero is an illustrated marketing preview. The authentication, studio and customizer screens below
are real captures of the working plugin. MeUI is an independent community theme by
[Saivra Technologies](https://github.com/saivra), shown in a local copy of the
[official Filament demo](https://github.com/filamentphp/demo) with seeded demonstration data.

## Designed across the application

- **A coherent shell.** Navigation, tenant switching, global search, notifications and profile actions.
- **Light and dark appearance.** Consistent tables, forms, records, dialogs, notices and chart containers.
- **Personal customization.** Live preview and database-backed preferences for signed-in users, enabled and controlled by the panel provider.
- **Your application's identity.** Native logos, fonts, primary colors, guards, tenancy, MFA and permissions.
- **A working component studio.** Explore native controls, validation, records, dialogs and banner rails.
- **A small integration.** Filament, Livewire and ordinary CSS, with no additional JavaScript framework, tracking or license service.

## Install

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
        // Your existing panel configuration...
        ->plugin(MeUiPlugin::make());
}
```

Laravel discovers the service provider automatically. A standard Filament panel needs no Node build.
For an existing Vite theme, follow the [custom-theme setup](https://github.com/saivra/meui-theme/blob/main/docs/installation.md#existing-vite-themes).
Run `filament:assets` after package upgrades and during deployment.

## Make the workspace yours

The optional customizer lets signed-in users preview and save their own neutral palette, accent,
corners, density, card treatment, navigation surface, workspace layout and contrast. Changes apply
immediately after saving and survive a reload. Reset restores the panel provider's current defaults.

| Personal appearance — light | Personal appearance — dark |
| --- | --- |
| ![MeUI personal customizer in light mode](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/customizer-light.jpg) | ![MeUI personal customizer in dark mode](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/customizer-dark.jpg) |

Publish the optional preferences migration before enabling the page:

```shell
php artisan vendor:publish --tag=meui-migrations
php artisan migrate
```

```php
MeUiPlugin::make()->customizer();
```

This adds **Customize theme** to the profile menu for every signed-in user. Preferences are isolated by
panel, guard and user. The theme and studio need no migration when personal customization is disabled.

Developers choose the defaults, audience and available controls in the panel provider:

```php
MeUiPlugin::make()
    ->palette('slate')
    ->accent('panel')
    ->radius('rounded')
    ->surface('elevated')
    ->sidebarStyle('soft')
    ->workspace('inset')
    ->density('comfortable')
    ->contrast('standard')
    ->customizer()
    ->customizerOptions(['palette', 'radius', 'density', 'contrast']);
```

Options also accept request-time closures for application settings. Restrict access with
`customizer(authorize: fn (): bool => auth()->user()?->can('customizeTheme') ?? false)` after defining the
ability in your application. You can connect an existing database settings store through `preferences(load: ..., save: ...)`.
See the [customization guide](https://github.com/saivra/meui-theme/blob/main/docs/customization.md)
and [mobile preview](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/customizer-mobile.jpg).

## A polished first impression

The split authentication layout accompanies native Filament sign-in, registration, recovery,
verification and MFA pages. Authentication, validation and access rules remain in your application.

| Sign in — light | Sign in — dark |
| --- | --- |
| ![Native Filament sign-in with MeUI in light mode](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/auth-login-light.jpg) | ![Native Filament sign-in with MeUI in dark mode](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/auth-login-dark.jpg) |

| Registration | Account recovery |
| --- | --- |
| ![Native registration with MeUI](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/auth-register-light.jpg) | ![Native password recovery with MeUI](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/auth-recovery-dark.jpg) |

Use `authAside(false)` for a panel with an existing custom authentication layout.

## MeUI Studio

The studio is an interactive reference for the components that make up a complete application:
searchable tables, filters, native form tabs and validation, record details, dialogs, empty states,
appearance controls and application notices. All studio data is fictitious.

| Data workspace | Native forms |
| --- | --- |
| ![MeUI Studio data workspace](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/studio-table-light.jpg) | ![MeUI Studio forms in dark mode](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/studio-form-dark.jpg) |

| Notice components | Focused dialogs |
| --- | --- |
| ![MeUI Studio semantic banners](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/studio-banners-light.jpg) | ![MeUI Studio native dialog](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/studio-dialog-dark.jpg) |

The studio is disabled by default. Enable it for an intended audience with an authorization closure:

```php
MeUiPlugin::make()->gallery(
    fn (): bool => auth()->user()?->can('viewComponentStudio') ?? false,
);
```

Define the ability in your application. Entry and interactive actions require authentication and a
successful authorization check. The studio does not query or save your application's business records.

## Native components, consistently styled

Use your existing Filament resources, forms, tables, infolists, actions and notifications. MeUI styles
their surfaces and states while preserving Filament's behavior. This includes rich editors, uploads,
repeaters, builders, wizards, relation managers, modal forms and slide-overs.

| Wizard | Product editing |
| --- | --- |
| ![Native order wizard with MeUI](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/components-wizard-light.jpg) | ![Product editing with MeUI](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/product-editor-light.jpg) |

Dedicated notice rails and five semantic banner tones support application messages. The package also
includes `page-intro`, `frame` and `empty-state` Blade components. See
[components and notices](https://github.com/saivra/meui-theme/blob/main/docs/components.md).

## Requirements and integration

| Dependency | Supported versions |
| --- | --- |
| PHP | 8.2+ on Laravel 12; 8.3+ on Laravel 13 |
| Laravel | 12 and 13 |
| Filament | 5.10.2 or later within 5.x |
| Browser | Current Chrome, Edge, Firefox or Safari with modern CSS support |

The [compatibility matrix](https://github.com/saivra/meui-theme/actions/workflows/tests.yml) tests
Laravel 12 and 13, minimum and current Filament versions, and SQLite and PostgreSQL.

Keep your panel's original shell with `shell(false)`. Branding, colors, fonts, guards, tenancy and
permissions stay configured through Filament. MeUI includes visible focus treatments, mobile control
sizing, semantic notices, reduced-motion and forced-colors styles. Check your completed application
with its content, colors and additional plugins; see [tested scope](https://github.com/saivra/meui-theme/blob/main/docs/compatibility.md).

## Documentation and support

| Guide | Contents |
| --- | --- |
| [Installation](https://github.com/saivra/meui-theme/blob/main/docs/installation.md) | Setup, Vite themes, deployment, upgrades and troubleshooting. |
| [Customization](https://github.com/saivra/meui-theme/blob/main/docs/customization.md) | Panel defaults, personal controls, database storage, access and integration callbacks. |
| [Components](https://github.com/saivra/meui-theme/blob/main/docs/components.md) | Notice rails, Blade helpers and the component studio. |
| [Gallery](https://github.com/saivra/meui-theme/blob/main/docs/screenshots.md) | Authentication, customization and application screens. |
| [Compatibility](https://github.com/saivra/meui-theme/blob/main/docs/compatibility.md) | Supported versions, test scope and integration limits. |

Open a [GitHub issue](https://github.com/saivra/meui-theme/issues) with your versions, browser and a small
reproduction. Use [private security reporting](https://github.com/saivra/meui-theme/blob/main/SECURITY.md)
for vulnerabilities. Development instructions are in
[CONTRIBUTING.md](https://github.com/saivra/meui-theme/blob/main/CONTRIBUTING.md).

MeUI is in the `0.x` series. Minor releases may change public APIs; patch releases are intended for
compatible fixes. Review the [changelog](https://github.com/saivra/meui-theme/blob/main/CHANGELOG.md) before upgrading.

<details>
<summary>Original demo, illustrated comparisons and launch artwork</summary>

The illustrated previews explore an enhanced visual direction, including an ApexCharts-style chart
composition. The theme uses the application's chart components and does not install or replace a chart library.

![Light mode illustrated comparison](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/comparison-light.jpg)

![Dark mode illustrated comparison](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/comparison-dark.jpg)

| Original Filament demo | MeUI applied, initial release |
| --- | --- |
| ![Original Filament demo, light](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/original-dashboard-light.jpg) | ![Initial MeUI demo, light](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/shop-dashboard-light.jpg) |
| ![Original Filament demo, dark](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/original-dashboard-dark.jpg) | ![Initial MeUI demo, dark](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/shop-dashboard-dark.jpg) |

![Orders marketing preview](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/orders-light-preview.jpg)

<img class="filament-hidden" src="https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/meui-thumbnail.jpg" alt="Directory thumbnail" />

[Main image: 2560×1440 JPEG](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/meui-listing.jpg)
· [Thumbnail: 1280×720 JPEG](https://raw.githubusercontent.com/saivra/meui-theme/main/docs/images/meui-thumbnail.jpg)

</details>

Created and maintained by [Ghulam Mustafa](https://github.com/GMustafa-s) at
[Saivra Technologies](https://github.com/saivra). Released under the
[MIT license](https://github.com/saivra/meui-theme/blob/main/LICENSE).
