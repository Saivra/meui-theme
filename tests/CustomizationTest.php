<?php

namespace Saivra\MeUi\Tests;

use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Saivra\MeUi\MeUiPlugin;
use Saivra\MeUi\Pages\ThemeSettings;
use Saivra\MeUi\Support\PreferenceStore;

class CustomizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        (require __DIR__.'/../database/migrations/2026_10_24_000000_create_meui_preferences_table.php')->up();
    }

    protected function theme(): MeUiPlugin
    {
        $plugin = Filament::getCurrentPanel()->getPlugin('meui');
        self::assertInstanceOf(MeUiPlugin::class, $plugin);

        return $plugin;
    }

    #[Test]
    public function customization_is_disabled_until_registered_and_needs_no_table_when_disabled(): void
    {
        $this->operator();
        Schema::drop('meui_preferences');

        self::assertFalse($this->theme()->canCustomize());
        self::assertNotContains(ThemeSettings::class, Filament::getCurrentPanel()->getPages());
        self::assertSame('slate', $this->theme()->getThemePreferences()['palette']);
    }

    #[Test]
    public function developer_defaults_can_be_resolved_from_current_application_settings(): void
    {
        $palette = 'stone';
        $theme = MeUiPlugin::make()->palette(function () use (&$palette): string {
            return $palette;
        })->accent('indigo')->radius('soft')->surface('outlined')->sidebarStyle('solid')->workspace('flush')->contrast('strong');

        self::assertSame('stone', $theme->getThemePreferences()['palette']);
        $palette = 'zinc';
        self::assertSame('zinc', $theme->getThemePreferences()['palette']);
        self::assertSame('indigo', $theme->getThemePreferences()['accent']);

        $palette = 'injected; color: red';
        $this->expectException(InvalidArgumentException::class);
        $theme->getThemePreferences();
    }

    #[Test]
    public function it_persists_only_the_authenticated_users_preferences_in_the_current_panel(): void
    {
        $first = $this->operator();
        $theme = $this->theme()->customizer();
        $theme->persistPreferences(['palette' => 'stone', 'accent' => 'violet', 'radius' => 'soft']);

        self::assertSame('stone', $theme->getThemePreferences()['palette']);
        $this->assertDatabaseCount('meui_preferences', 1);
        $this->get('/meui/meui-studio')->assertOk()->assertSee('data-palette="stone"', false);

        $second = $this->operator();
        self::assertSame('slate', $theme->getThemePreferences()['palette']);
        $theme->persistPreferences(['palette' => 'zinc']);
        $this->assertDatabaseCount('meui_preferences', 2);

        $this->actingAs($first);
        self::assertSame('stone', $theme->getThemePreferences()['palette']);
        Filament::setCurrentPanel(Panel::make()->id('other')->plugin(MeUiPlugin::make()->customizer()));
        self::assertSame('slate', Filament::getCurrentPanel()->getPlugin('meui')->getThemePreferences()['palette']);
        self::assertNotSame($first->getKey(), $second->getKey());
    }

    #[Test]
    public function it_ignores_corrupt_or_obsolete_saved_options_instead_of_rendering_them(): void
    {
        $this->operator();
        $theme = $this->theme()->customizer();
        app(PreferenceStore::class)->write(['palette' => 'untrusted', 'accent' => 'violet', 'unknown' => 'value']);
        request()->attributes->remove('meui.preferences.'.app(PreferenceStore::class)->key());

        self::assertSame('slate', $theme->getThemePreferences()['palette']);
        self::assertSame('violet', $theme->getThemePreferences()['accent']);
        self::assertArrayNotHasKey('unknown', $theme->getThemePreferences());
    }

    #[Test]
    public function provider_restrictions_override_previously_saved_values_and_reject_forged_changes(): void
    {
        $this->operator();
        $theme = $this->theme()->customizer();
        $theme->persistPreferences(['palette' => 'stone', 'density' => 'compact']);
        $theme->customizerOptions(['density', 'radius']);

        self::assertSame('slate', $theme->getThemePreferences()['palette']);
        self::assertSame('compact', $theme->getDensity());
        $this->expectException(ValidationException::class);
        $theme->persistPreferences(['palette' => 'zinc']);
    }

    #[Test]
    public function custom_persistence_can_use_the_hosts_settings_store_without_the_optional_table(): void
    {
        $this->operator();
        Schema::drop('meui_preferences');
        $stored = [];
        $theme = $this->theme()->customizer()->preferences(
            load: function () use (&$stored): array {
                return $stored;
            },
            save: function (array $values) use (&$stored): void {
                $stored = $values;
            },
        );

        $theme->persistPreferences(['palette' => 'zinc']);
        self::assertSame('zinc', $theme->getThemePreferences()['palette']);
        $theme->persistPreferences([]);
        self::assertSame('slate', $theme->getThemePreferences()['palette']);
    }

    #[Test]
    public function the_live_customizer_validates_saves_and_resets_personal_preferences(): void
    {
        $this->operator();
        $this->theme()->customizer()->palette('stone');

        Livewire::test(ThemeSettings::class)->assertSee('Make it yours')->assertSee('Live preview')
            ->fillForm(['palette' => 'zinc', 'accent' => 'violet', 'radius' => 'soft', 'density' => 'compact'])
            ->call('save')->assertHasNoFormErrors()->assertNotified('Appearance saved')
            ->assertDispatched('meui-theme-updated')
            ->call('resetPreferences')->assertHasNoFormErrors()->assertNotified('Default appearance restored')
            ->assertFormSet(['palette' => 'stone', 'density' => 'comfortable']);

        $this->assertDatabaseCount('meui_preferences', 0);
    }

    #[Test]
    public function a_changed_authorization_policy_prevents_customizer_writes(): void
    {
        $user = $this->operator();
        $this->theme()->customizer(fn (): bool => (bool) auth()->user()?->can_preview);
        $component = Livewire::test(ThemeSettings::class);
        $user->update(['can_preview' => false]);

        $component->call('save')->assertForbidden();
        $this->assertDatabaseCount('meui_preferences', 0);
    }

    #[Test]
    public function guests_and_unpermitted_users_cannot_open_the_customizer(): void
    {
        $this->theme()->customizer(fn (): bool => (bool) auth()->user()?->can_preview);
        self::assertFalse(ThemeSettings::canAccess());
        $this->operator(false);
        Livewire::test(ThemeSettings::class)->assertForbidden();
    }

    #[Test]
    public function it_never_writes_unknown_or_invalid_values(): void
    {
        $this->operator();
        $theme = $this->theme()->customizer();

        foreach ([['palette' => 'bad'], ['accent' => ['blue']], ['owner' => 'another-user']] as $forged) {
            try {
                $theme->persistPreferences($forged);
                self::fail('Invalid preferences were accepted.');
            } catch (ValidationException) {
                self::assertSame(0, DB::table('meui_preferences')->count());
            }
        }
    }

    #[Test]
    public function choices_outside_the_provider_allowlist_are_absent_from_the_form(): void
    {
        $this->operator();
        $this->theme()->customizer()->customizerOptions(['density', 'radius']);

        Livewire::test(ThemeSettings::class)->assertSee('Density')->assertSee('Corners')
            ->assertDontSee('Neutral palette')->assertDontSee('Accent color')
            ->fillForm(['density' => 'compact', 'radius' => 'soft'])->call('save')->assertHasNoFormErrors();
        self::assertSame('compact', $this->theme()->getDensity());
    }
}
