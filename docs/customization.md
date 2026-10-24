# Customization

[Back to the README](../README.md) · [Installation](installation.md) · [Components and notices](components.md)

MeUI follows the configuration of the panel that enables it. Start with Filament's native panel APIs
for your application's identity and behavior.

## Branding

```php
use Filament\Support\Colors\Color;
use Saivra\MeUi\MeUiPlugin;

return $panel
    ->brandName('Acme')
    ->brandLogo(asset('images/acme-logo.svg'))
    ->darkModeBrandLogo(asset('images/acme-logo-light.svg'))
    ->colors(['primary' => Color::Teal])
    ->plugin(MeUiPlugin::make());
```

Your panel continues to own its name, logos, colors, navigation groups, menus, guards, permissions,
tenancy and authentication. MeUI's full shell presents the native navigation, tenant menu, global search,
notifications and user actions.

## Appearance and density

Light, dark and system appearance use Filament's native preference controls in the profile menu.
Your application's existing dark-mode configuration still applies.

```php
MeUiPlugin::make()->density('compact');
```

`comfortable` is the default. Invalid density values throw an `InvalidArgumentException`.
Store any persistent user-facing density preference through your application's settings system.
The component studio's selector is a temporary preview.

## Keep an existing shell

```php
MeUiPlugin::make()->shell(false);
```

This preserves your panel's sidebar and topbar components and dimensions while retaining MeUI's
component styling. With the full shell enabled, MeUI sets the sidebar to `17.5rem` and the collapsed
sidebar to `5rem`. Top-navigation panels use Filament's native topbar within the notice rail.

## Authentication layout

The split aside is enabled on native sign-in, registration, recovery, verification and MFA simple pages.
Disable it when your panel already provides a custom authentication layout:

```php
MeUiPlugin::make()->authAside(false);
```

MeUI does not change authentication validation or account access rules.

## Translations

Shell and authentication copy use namespaced translations. Override the included English strings in
your application at `lang/vendor/meui/en/ui.php`, using the package's
[`resources/lang/en/ui.php`](../resources/lang/en/ui.php) as the key reference.
Add the corresponding locale directory for another language. The optional studio's sample content is English.

## CSS variables

The stylesheet exposes these surface variables:

| Variable | Purpose |
| --- | --- |
| `--meui-canvas` | Outer workspace background. |
| `--meui-surface` | Main component surfaces. |
| `--meui-subtle` | Secondary surface emphasis. |
| `--meui-hover` | Hover background. |
| `--meui-border` | Surface and component borders. |
| `--meui-ink` | Primary text. |
| `--meui-muted` | Secondary text. |
| `--meui-accent` | Accent treatment derived from the panel's primary color. |
| `--meui-radius` | Surface corner radius. |
| `--meui-control-radius` | Control corner radius. |

Inspect the included stylesheet before overriding variables. Scope overrides to your themed panel and
check both appearances, focused controls, validation messages and disabled states. Brand colors and
additional plugins can affect contrast, so validate the finished application.

MeUI includes visible focus treatments, semantic notice roles, logical spacing, reduced-motion rules
and print styles. Native keyboard and modal behavior remain in Filament. See
[compatibility](compatibility.md) for the boundaries of the tested integration.
