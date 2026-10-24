<?php

namespace Saivra\MeUi\Tests;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Saivra\MeUi\Tests\Fixtures\ComponentLab;

class ComponentTest extends TestCase
{
    #[Test]
    #[DataProvider('bannerTones')]
    public function it_renders_semantic_banners_without_unescaped_content(string $tone, string $role): void
    {
        $html = Blade::render('<x-meui::banner :tone="$tone" :heading="$heading" :description="$description" />', [
            'tone' => $tone, 'heading' => '<script>example</script>', 'description' => '<img src=x onerror=example>',
        ]);

        self::assertStringContainsString('role="'.$role.'"', $html);
        self::assertStringContainsString('&lt;script&gt;', $html);
        self::assertStringContainsString('&lt;img', $html);
        self::assertStringNotContainsString('<script>', $html);
        self::assertStringNotContainsString('<img src=x', $html);
    }

    public static function bannerTones(): array
    {
        return [['info', 'status'], ['success', 'status'], ['announcement', 'status'], ['warning', 'alert'], ['danger', 'alert']];
    }

    #[Test]
    public function it_preserves_custom_attributes_and_banner_controls(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-meui::banner tone="unknown" id="service-notice" role="status">
                <x-slot name="controls"><button>Dismiss</button></x-slot>
                Service update
            </x-meui::banner>
            BLADE);

        self::assertStringContainsString('id="service-notice"', $html);
        self::assertStringContainsString('data-tone="info"', $html);
        self::assertStringContainsString('Dismiss', $html);
    }

    #[Test]
    public function it_composes_frames_and_empty_states_with_host_actions(): void
    {
        $html = Blade::render('<x-meui::frame title="People"><x-meui::empty-state title="No people yet" description="Add your first teammate."><button>Add person</button></x-meui::empty-state><x-slot name="footer">0 people</x-slot></x-meui::frame>');

        self::assertStringContainsString('No people yet', $html);
        self::assertStringContainsString('Add person', $html);
        self::assertStringContainsString('0 people', $html);
    }

    #[Test]
    public function it_renders_complex_native_components_in_a_clean_consumer(): void
    {
        $this->operator();
        Livewire::test(ComponentLab::class)->assertSee('Workspace')->assertSee('People')->assertSee('Content')
            ->assertSee('Add to members')->assertSee('Add to blocks')->assertSee('Attachment')
            ->mountAction('review')->assertMountedActionModalSee('Review workspace')->unmountAction();
    }

    #[Test]
    public function it_keeps_nested_form_validation_and_reset_behavior(): void
    {
        $this->operator();
        Livewire::test(ComponentLab::class)->fillForm(['name' => '', 'members' => [['name' => '', 'role' => 'member']]])
            ->call('validateExample')->assertHasFormErrors(['name' => 'required']);
    }
}
