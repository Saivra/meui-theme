# Customization

[Back to the README](../README.md) · [Installation](installation.md) · [Components](components.md)

MeUI offers panel defaults for developers and an optional personal appearance page for signed-in users.
Branding, navigation, guards and application permissions remain configured through Filament.

## Developer defaults

```php
use Saivra\MeUi\MeUiPlugin;

MeUiPlugin::make()
    ->palette('slate')
    ->accent('panel')
    ->radius('rounded')
    ->surface('elevated')
    ->sidebarStyle('soft')
    ->workspace('inset')
    ->density('comfortable')
    ->contrast('standard');
```

| Method | Choices | Default |
| --- | --- | --- |
| `palette()` | `slate`, `zinc`, `stone` | `slate` |
| `accent()` | `panel`, `blue`, `indigo`, `violet`, `teal`, `rose` | `panel` |
| `radius()` | `compact`, `rounded`, `soft` | `rounded` |
| `surface()` | `elevated`, `outlined` | `elevated` |
| `sidebarStyle()` | `soft`, `solid` | `soft` |
| `workspace()` | `inset`, `flush` | `inset` |
| `density()` | `comfortable`, `compact` | `comfortable` |
| `contrast()` | `standard`, `strong` | `standard` |

Every method also accepts a closure, evaluated against the current request. This lets your application
resolve defaults from its database settings. Return a supported value; invalid defaults throw an
`InvalidArgumentException`. Free-form CSS is not accepted as a saved appearance preference.

`accent('panel')` follows the primary color configured by the application. An accent preset changes the
primary palette throughout the themed panel; semantic success, warning and danger colors retain their meaning.

## Personal customization for signed-in users

The customizer is **off by default**. To use its database-backed storage, publish the optional migration
and run it before enabling the page:

```shell
php artisan vendor:publish --tag=meui-migrations
php artisan migrate
php artisan filament:assets
```

Then enable it in the panel provider:

```php
MeUiPlugin::make()->customizer();
```

Every signed-in user of that panel can open **Customize theme** from their profile menu. The live preview
updates while they choose options. **Save preferences** applies the appearance and stores it in the database;
**Reset to defaults** clears their saved choices and restores the provider's current defaults.

Preferences are isolated by panel, authentication guard, user model class and authenticated identifier.
They follow the same user across tenants within that panel. They do not change another user's appearance
or the application's shared branding. Owner identifiers are derived on the server, never accepted from the form.

The table stores a scope hash, the validated preference values and timestamps. When customization is disabled,
MeUI does not read or write this table. The theme and studio work without the optional migration.

### Restrict access

```php
MeUiPlugin::make()->customizer(
    authorize: fn (): bool => auth()->user()?->can('customizeTheme') ?? false,
);
```

Define that ability in your application. Authentication and the authorization callback are checked again
when the user saves or resets. Use `customizer(false)` to disable the page entirely.

### Choose the available controls

```php
MeUiPlugin::make()
    ->accent('panel')
    ->customizer()
    ->customizerOptions(['density', 'palette', 'radius', 'contrast']);
```

The full option keys are `density`, `palette`, `accent`, `radius`, `surface`, `sidebar`, `workspace` and
`contrast`. Only the selected controls appear. Values outside the allowlist are rejected on save, and old
saved choices for controls you remove no longer override your defaults. This lets you keep the application's
accent and layout fixed while allowing personal density, neutral colors and corners.

### Use an existing database settings system

Provide both callbacks to use your application's store instead of MeUI's optional table:

```php
use Filament\Facades\Filament;

MeUiPlugin::make()
    ->customizer()
    ->preferences(
        load: fn (): array => Filament::auth()->user()->appearance_preferences ?? [],
        save: fn (array $values) => Filament::auth()->user()->update([
            'appearance_preferences' => $values,
        ]),
    );
```

This example assumes an `appearance_preferences` JSON column with an `array` cast and appropriate model
assignment rules. You can call your own settings service instead. Scope the callbacks to the authenticated
user and current panel. Saving receives validated option values; resetting receives an empty array.
The optional MeUI migration is not needed with these callbacks.

## Branding and native layout

```php
use Filament\Support\Colors\Color;

return $panel
    ->brandName('Acme')
    ->brandLogo(asset('images/acme-logo.svg'))
    ->darkModeBrandLogo(asset('images/acme-logo-light.svg'))
    ->colors(['primary' => Color::Teal])
    ->plugin(MeUiPlugin::make())
    ->sidebarWidth('18rem');
```

Use Filament's native APIs for fonts, logos, navigation and content width. Set a custom sidebar width after
registering the plugin to override MeUI's defaults of `17.5rem` expanded and `5rem` collapsed.

Light, dark and system mode use Filament's native appearance controls and browser preference. MeUI's
database preferences cover its eight customization options.

## Keep an existing shell or authentication layout

```php
MeUiPlugin::make()
    ->shell(false)
    ->authAside(false);
```

`shell(false)` preserves your sidebar and topbar components and dimensions while keeping component styling.
Top-navigation panels use Filament's native topbar within the notice rail. The split authentication aside
normally accompanies native sign-in, registration, recovery, verification and MFA pages; disable it if your
application already provides its own layout. Authentication and access rules remain native.

## Translations and CSS variables

Override strings in `lang/vendor/meui/en/ui.php`, using
[`resources/lang/en/ui.php`](../resources/lang/en/ui.php) as the key reference. Add the corresponding locale
directory for another language. The included studio examples are English.

Surface variables include `--meui-canvas`, `--meui-surface`, `--meui-subtle`, `--meui-hover`, `--meui-border`,
`--meui-control-border`, `--meui-ink`, `--meui-muted`, `--meui-accent`, `--meui-radius`,
`--meui-workspace-radius`, `--meui-control-radius` and `--meui-shadow`.

Inspect the included stylesheets before overriding them. Scope overrides to your themed panel and check
both appearances, focused controls, validation and disabled states. MeUI includes focus treatments, semantic
notice roles, mobile touch targets, reduced-motion and forced-colors rules. Validate the finished application
with its brand colors and additional plugins; see [compatibility](compatibility.md).
