# Compatibility

MeUI supports Filament 5.10.2 and later within 5.x on Laravel 12 and 13. Laravel 12 supports PHP 8.2+;
Laravel 13 requires PHP 8.3+. The CI matrix exercises minimum/current Filament and supported Laravel
versions with SQLite and PostgreSQL. Check the latest workflow results before relying on a combination.

The independent consumer covers shell registration and opt-out, native sign-in markup, profile/menu
rendering, panel isolation, stylesheet publication, studio authorization and interactive changes,
table operations, nested forms, banners, dialogs and reusable composition components.

The component lab includes a repeater, builder, rich editor, file upload, wizard and slide-over. Native
behavior belongs to Filament. Runtime package styling is not a replacement for validation, upload rules
or authorization in the host application.

Browser support requires native CSS nesting, `:has()`, logical properties and `color-mix()`.
No polyfills are included. MeUI includes reduced-motion and print rules. It does not claim formal
accessibility certification or compatibility with every third-party Filament plugin.

Check custom forms, relation managers, dense tables, charts, top navigation, additional languages and
custom simple-page layouts in your application. These use public Filament hooks, which can change
between upstream releases. Third-party themes or global CSS overrides can conflict with MeUI.
