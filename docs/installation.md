# Installation

[Back to the README](../README.md) · [Customization](customization.md) · [Compatibility](compatibility.md)

MeUI supports Filament 5.10.2+ on Laravel 12 and 13. Install it into an existing Filament panel application.

## Install the package

```shell
composer require saivra/meui-theme
php artisan filament:assets
```

Laravel discovers `MeUiServiceProvider` automatically. Register the plugin in the panel provider:

```php
use Filament\Panel;
use Saivra\MeUi\MeUiPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // Keep your existing panel configuration.
        ->plugin(MeUiPlugin::make());
}
```

Enable the plugin separately in each panel. Other panels keep their existing appearance.

MeUI publishes its stylesheet through Filament's asset system. A standard panel using Filament's
precompiled theme does not need npm, a CSS import or an additional JavaScript build for MeUI.

## Existing Vite themes

Keep your theme when your application or another plugin needs its utility classes. Add MeUI's views
to Tailwind's source discovery and rebuild your assets. For the usual Filament theme location:

```css
/* resources/css/filament/admin/theme.css */
@import '../../../../vendor/filament/filament/resources/css/theme.css';

@source '../../../../app/Filament/**/*';
@source '../../../../resources/views/filament/**/*';
@source '../../../../vendor/saivra/meui-theme/resources/views/**/*.blade.php';
```

Adjust the relative paths if your stylesheet lives elsewhere. Keep your existing source declarations
and Vite inputs, then run your normal production build, usually `npm run build`.

Remove a competing theme engine before enabling MeUI. Themes that style the same Filament elements
can override one another unpredictably.

## Deployment and upgrades

Commit your application's Composer lock file. During deployment, install the locked dependencies,
publish the Filament assets and clear stale views:

```shell
composer install --no-dev --prefer-dist --optimize-autoloader
php artisan filament:assets
php artisan view:clear
```

Run your existing application cache and Vite build steps as usual. MeUI has no migrations or setup command.

To upgrade within the constraint selected by your application:

```shell
composer update saivra/meui-theme --with-all-dependencies
php artisan filament:assets
php artisan view:clear
```

Read the [changelog](../CHANGELOG.md), inspect the dependency changes and check each panel before deployment.
In the `0.x` series, a minor release may change public APIs.

## Troubleshooting

| Symptom | Check |
| --- | --- |
| The panel still has its original appearance | Register `MeUiPlugin` in that panel, publish Filament assets and refresh the browser cache. |
| Styles or markup look stale after an upgrade | Run `filament:assets` and `view:clear`; check any CDN or application asset cache. |
| A custom theme misses utility styles | Include MeUI's views in Tailwind source discovery and rebuild the existing Vite theme. |
| Colors or spacing conflict | Check other theme plugins, global CSS overrides and previously published Filament views. |
| A custom authentication page has two side panels | Use `authAside(false)` for that panel. |
| An existing shell must retain its dimensions | Use `shell(false)` to keep your own sidebar, topbar and width settings. |
| Composer cannot select a version | Confirm the PHP, Laravel and Filament requirements, including other plugins that pin Filament versions. |

For a reproducible issue, include your PHP, Laravel, Filament and MeUI versions, browser and the smallest
example you can share. Use the [issue tracker](https://github.com/saivra/meui-theme/issues).
