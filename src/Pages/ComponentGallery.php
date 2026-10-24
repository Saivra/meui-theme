<?php

namespace Saivra\MeUi\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Saivra\MeUi\Livewire\Topbar;
use Saivra\MeUi\MeUiPlugin;

/**
 * Fictitious reference workflows. No application models or persistence.
 *
 * @property-read Schema $form Filament's lazily resolved named form schema.
 */
class ComponentGallery extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationLabel = 'MeUI studio';

    protected static ?string $title = 'Component studio';

    protected static ?string $slug = 'meui-studio';

    protected ?string $subheading = 'A working collection of the details that make an application feel considered.';

    protected string $view = 'meui::pages.gallery';

    #[Locked]
    public string $screen = 'table';

    /** @var array<string, mixed> */
    public array $data = [];

    #[Locked]
    public bool $saved = false;

    #[Locked]
    public string $recordName = 'Northstar workspace';

    public static function canAccess(): bool
    {
        $panel = Filament::getCurrentPanel();
        $plugin = $panel?->hasPlugin('meui') ? $panel->getPlugin('meui') : null;

        return $plugin instanceof MeUiPlugin && $plugin->canAccessGallery();
    }

    public function mount(): void
    {
        $this->authorizeGallery();
        $this->form->fill($this->defaults());
    }

    public function selectScreen(string $screen): void
    {
        $this->authorizeGallery();
        abort_unless(in_array($screen, ['table', 'form', 'record', 'auth', 'banners', 'foundations'], true), 404);
        $this->screen = $screen;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Tabs::make('Workspace settings')->contained(false)->tabs([
                Tab::make('General')->icon('heroicon-o-adjustments-horizontal')->schema([
                    Section::make('Workspace details')->description('The essentials people use to recognize your workspace.')
                        ->columns(2)->schema([
                            TextInput::make('name')->label('Workspace name')->required()->maxLength(80),
                            TextInput::make('email')->label('Contact email')->email()->required()->maxLength(120),
                            Select::make('region')->options(['americas' => 'Americas', 'europe' => 'Europe', 'asia' => 'Asia Pacific'])->native(false)->required(),
                            DatePicker::make('review_date')->label('Next review')->native(false)->required(),
                            Textarea::make('description')->rows(3)->maxLength(500)->columnSpanFull()
                                ->helperText('A short introduction for your team. Up to 500 characters.'),
                        ]),
                ]),
                Tab::make('Notifications')->icon('heroicon-o-bell')->schema([
                    Section::make('Notifications')->description('Choose how this example workspace keeps people informed.')->schema([
                        CheckboxList::make('updates')->label('Include in the weekly digest')
                            ->options(['activity' => 'Team activity', 'reviews' => 'Upcoming reviews', 'changes' => 'Workspace changes'])->columns(3),
                        Toggle::make('digest')->label('Send a weekly summary')->helperText('One useful update, every Monday morning.'),
                    ]),
                ]),
            ]),
        ]);
    }

    public function record(Schema $schema): Schema
    {
        $example = $this->selectedExample();

        return $schema->constantState([
            'name' => $example['name'], 'status' => $example['status'], 'owner' => $example['owner'],
            'email' => $example['email'], 'region' => 'Americas', 'updated' => $example['updated'],
            'description' => 'A shared workspace for a distributed operations team. Reviews, planning, and daily work stay connected.',
        ])->components([
            Section::make('Overview')->description('The important details, in one place.')->columns(3)->schema([
                TextEntry::make('name')->label('Workspace'),
                TextEntry::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'Active' => 'success', 'In review' => 'warning', default => 'gray'
                }),
                TextEntry::make('region'),
                TextEntry::make('owner'),
                TextEntry::make('email')->label('Contact email'),
                TextEntry::make('updated')->label('Last updated')->date('F j, Y'),
                TextEntry::make('description')->columnSpanFull(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->records(function (?string $search, ?array $filters, ?string $sortColumn, ?string $sortDirection): Collection {
            $rows = collect($this->exampleRecords())->filter(fn (array $row): bool => (! filled($search) || str_contains(strtolower(implode(' ', $row)), strtolower($search))) &&
                (! filled($status = $filters['status']['value'] ?? null) || $row['status'] === $status));

            return $rows->sortBy(in_array($sortColumn, ['name', 'members', 'updated'], true) ? $sortColumn : 'name', descending: $sortDirection === 'desc');
        })
            ->heading('Workspaces')->description('Fictitious data. Explore the controls without changing application records.')
            ->columns([
                TextColumn::make('name')->label('Workspace')->description(fn (array $record): string => $record['reference'])->searchable()->sortable()->weight('medium'),
                TextColumn::make('owner')->label('Owner')->description(fn (array $record): string => $record['email']),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'Active' => 'success', 'In review' => 'warning', default => 'gray'
                }),
                TextColumn::make('members')->numeric()->sortable()->alignEnd(),
                TextColumn::make('updated')->label('Last updated')->date('M j, Y')->sortable(),
            ])
            ->filters([SelectFilter::make('status')->options(['Active' => 'Active', 'In review' => 'In review', 'Archived' => 'Archived'])])
            ->recordActions([
                Action::make('inspect')->label('View')->icon('heroicon-o-arrow-up-right')->color('gray')
                    ->action(function (array $record): void {
                        $this->authorizeGallery();
                        $this->recordName = $record['name'];
                        $this->screen = 'record';
                    }),
            ])
            ->stackedOnMobile()->paginated(false)->emptyStateHeading('No matching workspaces')
            ->emptyStateDescription('Try another name or clear the status filter.')->emptyStateIcon('heroicon-o-magnifying-glass');
    }

    public function saveExample(): void
    {
        $this->authorizeGallery();
        $this->form->getState();
        $this->saved = true;
        Notification::make()->title('Example validated')->body('Your changes are kept in this preview only.')->success()->send();
    }

    public function previewBanner(string $tone): void
    {
        $this->authorizeGallery();
        abort_unless(in_array($tone, ['info', 'success', 'warning', 'danger', 'announcement'], true), 404);
        $this->dispatch('meui-preview-banner', tone: $tone)->to(Topbar::class);
    }

    public function resetExample(): void
    {
        $this->authorizeGallery();
        $this->form->fill($this->defaults());
        $this->saved = false;
    }

    public function exampleDialogAction(): Action
    {
        return Action::make('exampleDialog')->label('Preview dialog')->color('gray')->outlined()
            ->modalHeading('A clear next step')->modalDescription('Dialogs keep the decision focused. This example does not change application data.')
            ->modalSubmitActionLabel('Looks good')
            ->action(function (): void {
                $this->authorizeGallery();
                Notification::make()->title('Example confirmed')->success()->send();
            });
    }

    /** @return array<string, mixed> */
    protected function defaults(): array
    {
        return ['name' => 'Northstar workspace', 'email' => 'team@example.test', 'region' => 'americas', 'review_date' => '2026-11-12', 'description' => 'A shared place for a distributed team to plan and do their best work.', 'updates' => ['activity', 'reviews'], 'digest' => true];
    }

    /** @return array<int, array<string, string|int>> */
    public function exampleRecords(): array
    {
        return [
            ['name' => 'Northstar workspace', 'reference' => 'WS-1048 · Operations', 'owner' => 'Mara Lind', 'email' => 'mara@example.test', 'status' => 'Active', 'members' => 24, 'updated' => '2026-10-22'],
            ['name' => 'Forma collective', 'reference' => 'WS-1049 · Design', 'owner' => 'Idris Chen', 'email' => 'idris@example.test', 'status' => 'Active', 'members' => 12, 'updated' => '2026-10-23'],
            ['name' => 'Meridian group', 'reference' => 'WS-1051 · Strategy', 'owner' => 'Elena Rossi', 'email' => 'elena@example.test', 'status' => 'In review', 'members' => 8, 'updated' => '2026-10-20'],
            ['name' => 'Fieldwork studio', 'reference' => 'WS-1053 · Research', 'owner' => 'Theo Park', 'email' => 'theo@example.test', 'status' => 'Active', 'members' => 17, 'updated' => '2026-10-21'],
            ['name' => 'Common ground', 'reference' => 'WS-1055 · Community', 'owner' => 'Amara Okafor', 'email' => 'amara@example.test', 'status' => 'In review', 'members' => 31, 'updated' => '2026-10-19'],
            ['name' => 'Archive project', 'reference' => 'WS-1032 · Planning', 'owner' => 'Soren Berg', 'email' => 'soren@example.test', 'status' => 'Archived', 'members' => 6, 'updated' => '2026-10-12'],
        ];
    }

    protected function authorizeGallery(): void
    {
        abort_unless(static::canAccess(), 403);
    }

    /** @return array<string, string|int> */
    public function selectedExample(): array
    {
        foreach ($this->exampleRecords() as $example) {
            if ($example['name'] === $this->recordName) {
                return $example;
            }
        }

        return $this->exampleRecords()[0];
    }
}
