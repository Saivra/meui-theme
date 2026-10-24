<?php

namespace Saivra\MeUi\Tests;

use Filament\Facades\Filament;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Saivra\MeUi\Livewire\Sidebar;
use Saivra\MeUi\Livewire\Topbar;
use Saivra\MeUi\MeUiPlugin;
use Saivra\MeUi\Pages\ComponentGallery;

class GalleryTest extends TestCase
{
    #[Test]
    public function it_denies_anonymous_and_unauthorized_visitors(): void
    {
        self::assertFalse(ComponentGallery::canAccess());
        $this->operator(preview: false);
        self::assertFalse(ComponentGallery::canAccess());
        Livewire::test(ComponentGallery::class)->assertForbidden();
        Livewire::test(Topbar::class)->call('previewBanner', 'danger')->assertForbidden();
    }

    #[Test]
    public function it_renders_a_real_profile_and_authorizes_banner_previews(): void
    {
        $this->operator();
        Livewire::test(Sidebar::class)->assertSee('Mara Lind')->assertSee('Sign out');
        Livewire::test(Topbar::class)->call('previewBanner', 'warning')->assertSet('previewTone', 'warning')
            ->assertSee('Component studio preview')->call('clearPreviewBanner')->assertSet('previewTone', null);
        Livewire::test(Topbar::class)->call('previewBanner', 'invalid')->assertNotFound();
    }

    #[Test]
    public function it_searches_filters_and_sorts_the_fictitious_table(): void
    {
        $this->operator();
        Livewire::test(ComponentGallery::class)->assertSee('Northstar workspace')->assertSee('Forma collective')
            ->searchTable('Northstar')->assertSee('Northstar workspace')->assertDontSee('Forma collective')
            ->searchTable('')->filterTable('status', 'Archived')->assertSee('Archive project')->assertDontSee('Northstar workspace')
            ->resetTableFilters()->sortTable('members', 'desc')->assertSeeInOrder(['Common ground', 'Northstar workspace', 'Fieldwork studio'])
            ->searchTable('missing-workspace')->assertSee('No matching workspaces');
    }

    #[Test]
    public function it_opens_the_selected_record_and_preserves_its_context(): void
    {
        $this->operator();
        Livewire::test(ComponentGallery::class)->searchTable('Forma')
            ->call('mountAction', 'inspect', [], ['table' => true, 'recordKey' => '1'])
            ->assertSet('recordName', 'Forma collective')->assertSee('Idris Chen')->assertSee('idris@example.test');
    }

    #[Test]
    public function it_validates_resets_and_operates_each_reference_screen(): void
    {
        $this->operator();
        $page = Livewire::test(ComponentGallery::class)->call('selectScreen', 'form')
            ->fillForm(['name' => '', 'email' => 'invalid'])->call('saveExample')
            ->assertHasFormErrors(['name' => 'required', 'email' => 'email'])
            ->call('resetExample')->call('saveExample')->assertHasNoFormErrors()->assertSet('saved', true)
            ->call('selectScreen', 'record')->assertSee('Overview')->assertSee('Mara Lind')
            ->call('selectScreen', 'auth')->assertSee('Welcome back')
            ->call('selectScreen', 'banners')->assertSee('Action is required to continue')
            ->call('previewBanner', 'success')->assertDispatched('meui-preview-banner', tone: 'success')
            ->call('selectScreen', 'foundations')->assertSee('A clean slate')
            ->mountAction('exampleDialog')->assertMountedActionModalSee('A clear next step')->unmountAction();

        $page->call('selectScreen', 'invalid')->assertNotFound();
    }

    #[Test]
    public function it_rechecks_studio_authorization_on_every_change(): void
    {
        $this->operator();
        $page = Livewire::test(ComponentGallery::class);
        Filament::getPanel('meui')->plugin(MeUiPlugin::make()->gallery(fn (): bool => false));
        $page->call('saveExample')->assertForbidden();
    }
}
