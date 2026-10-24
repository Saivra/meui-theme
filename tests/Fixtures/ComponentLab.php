<?php

namespace Saivra\MeUi\Tests\Fixtures;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

/** @property-read Schema $form */
class ComponentLab extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $title = 'Component lab';

    protected string $view = 'meui-tests::components';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill(['name' => 'Northstar workspace', 'members' => [['name' => 'Mara Lind', 'role' => 'owner']]]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Wizard::make([
                Step::make('Workspace')->schema([TextInput::make('name')->required()->maxLength(80)]),
                Step::make('People')->schema([
                    Repeater::make('members')->schema([
                        TextInput::make('name')->required(),
                        Select::make('role')->options(['owner' => 'Owner', 'member' => 'Member'])->required(),
                    ])->columns(2)->minItems(1),
                ]),
                Step::make('Content')->schema([
                    Section::make('Notes')->schema([
                        RichEditor::make('notes')->toolbarButtons(['bold', 'italic', 'bulletList', 'link']),
                        Builder::make('blocks')->blocks([
                            Block::make('text')->schema([TextInput::make('content')->required()]),
                        ]),
                        FileUpload::make('attachment')->disk('local')->visibility('private')->storeFiles(false)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'application/pdf'])->maxSize(1024),
                    ]),
                ]),
            ]),
        ]);
    }

    public function validateExample(): void
    {
        $this->form->getState();
    }

    public function reviewAction(): Action
    {
        return Action::make('review')->label('Open slide-over')->slideOver()
            ->modalHeading('Review workspace')->modalDescription('A component preview using fictitious data.')
            ->action(fn () => null);
    }
}
