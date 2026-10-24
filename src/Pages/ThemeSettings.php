<?php

namespace Saivra\MeUi\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Saivra\MeUi\MeUiPlugin;
use Saivra\MeUi\Support\ThemeOptions;

/** @property-read Schema $form */
class ThemeSettings extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'meui-appearance';

    protected string $view = 'meui::pages.theme-settings';

    /** @var array<string, mixed> */
    public array $data = [];

    public static function canAccess(): bool
    {
        $panel = Filament::getCurrentPanel();
        $plugin = $panel?->hasPlugin('meui') ? $panel->getPlugin('meui') : null;

        return $plugin instanceof MeUiPlugin && $plugin->canCustomize();
    }

    public function getTitle(): string
    {
        return __('meui::ui.customizer.title');
    }

    public function getSubheading(): string
    {
        return __('meui::ui.customizer.description');
    }

    public function mount(): void
    {
        $this->form->fill($this->plugin()->getThemePreferences());
    }

    protected function plugin(): MeUiPlugin
    {
        abort_unless(static::canAccess(), 403);
        $plugin = Filament::getCurrentPanel()?->getPlugin('meui');
        abort_unless($plugin instanceof MeUiPlugin, 403);

        return $plugin;
    }

    public function form(Schema $schema): Schema
    {
        $fields = [];

        foreach ($this->plugin()->getCustomizableOptions() as $name) {
            $choices = [];

            foreach (ThemeOptions::CHOICES[$name] as $value) {
                $choices[$value] = __('meui::ui.customizer.choices.'.$name.'.'.$value);
            }

            if ($name === 'accent') {
                $fields[] = ToggleButtons::make($name)->label(__('meui::ui.customizer.labels.'.$name))
                    ->options($choices)->colors([
                        'panel' => 'primary', 'blue' => Color::Blue, 'indigo' => Color::Indigo,
                        'violet' => Color::Violet, 'teal' => Color::Teal, 'rose' => Color::Rose,
                    ])->inline()->required()->live()->columnSpanFull();
            } else {
                $fields[] = Select::make($name)->label(__('meui::ui.customizer.labels.'.$name))
                    ->options($choices)->required()->selectablePlaceholder(false)->live();
            }
        }

        return $schema->statePath('data')->components([
            Section::make(__('meui::ui.customizer.controls'))->description(__('meui::ui.customizer.personal'))
                ->columns(2)->schema($fields),
        ]);
    }

    public function save(): void
    {
        $plugin = $this->plugin();
        $plugin->persistPreferences($this->form->getState());
        $this->apply($plugin);
        Notification::make()->title(__('meui::ui.customizer.saved'))->success()->send();
    }

    public function resetPreferences(): void
    {
        $plugin = $this->plugin();
        $plugin->persistPreferences([]);
        $this->form->fill($plugin->getThemePreferences());
        $this->apply($plugin);
        Notification::make()->title(__('meui::ui.customizer.reset_done'))->success()->send();
    }

    protected function apply(MeUiPlugin $plugin): void
    {
        $this->dispatch('meui-theme-updated', panel: Filament::getId(), preferences: $plugin->getThemePreferences());
    }

    /** @return array<string, string> */
    public function previewPreferences(): array
    {
        $plugin = $this->plugin();

        return array_replace($plugin->getThemePreferences(), array_intersect_key(
            ThemeOptions::sanitize($this->data), array_flip($plugin->getCustomizableOptions()),
        ));
    }
}
