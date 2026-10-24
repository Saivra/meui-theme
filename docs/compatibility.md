# Compatibility

MeUI supports Filament 5.10.2 and later within 5.x on Laravel 12 and 13. Laravel 12 supports PHP 8.2+;
Laravel 13 requires PHP 8.3+. The CI matrix exercises minimum/current Filament and supported Laravel
versions with SQLite and PostgreSQL. Check the latest workflow results before relying on a combination.

The independent consumer covers shell registration and opt-out, native sign-in markup, profile/menu
rendering, panel isolation, stylesheet publication, studio authorization and interactive changes,
table operations, nested forms, banners, dialogs and reusable composition components.

The optional personal customizer is tested on both database engines, including its published migration,
per-user and per-panel isolation, provider defaults and allowlists, revoked access, invalid stored data,
custom persistence callbacks, live form saves and resets. Its table is not required when customization
is disabled or the application supplies persistence callbacks.

The component lab includes a repeater, builder, rich editor, file upload, wizard and slide-over. Native
behavior belongs to Filament. Runtime package styling is not a replacement for validation, upload rules
or authorization in the host application.

Browser support requires native CSS nesting, `:has()`, logical properties and `color-mix()`.
No polyfills are included. MeUI includes visible focus, reduced-motion, forced-colors and print rules.
The customizer and native authentication have been checked in light and dark mode, including a 390px
mobile viewport. Dialog dismissal with Escape returns focus to the triggering control. It does not claim formal
accessibility certification or compatibility with every third-party Filament plugin.

MeUI styles native chart containers and includes matching presentation rules for an existing ApexCharts
integration. It does not install a chart library, replace widgets or change the application's metrics.

Check custom forms, relation managers, dense tables, charts, top navigation, additional languages and
custom simple-page layouts in your application. These use public Filament hooks, which can change
between upstream releases. Third-party themes or global CSS overrides can conflict with MeUI.
