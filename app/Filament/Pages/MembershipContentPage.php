<?php

namespace App\Filament\Pages;

use App\Models\ExternalLink;
use App\Models\Flyer;
use App\Models\MembershipDocument;
use App\Models\Page as PageModel;
use App\Models\PageSection;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MembershipContentPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?string $navigationLabel = 'Mitglied werden';

    protected static ?int $navigationSort = 15;

    protected static ?string $title = 'Mitglied werden';

    protected static string $routePath = 'membership-content';

    protected string $view = 'filament.pages.membership-content-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getFormDefaults());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Mitglied werden')
                    ->tabs([
                        Tab::make('Allgemein')
                            ->schema([
                                TextInput::make('general.page_title')
                                    ->label('Seitentitel')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('general.nav_label')
                                    ->label('Navigationslabel')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('general.hero_subtitle')
                                    ->label('Untertitel')
                                    ->maxLength(255),
                                Textarea::make('general.hero_intro')
                                    ->label('Einleitung')
                                    ->rows(5)
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('general.meta_title')
                                    ->label('Meta-Titel')
                                    ->maxLength(255),
                                Textarea::make('general.meta_description')
                                    ->label('Meta-Beschreibung')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Select::make('general.status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Entwurf',
                                        'published' => 'Veröffentlicht',
                                    ])
                                    ->required()
                                    ->native(false),
                                DateTimePicker::make('general.published_at')
                                    ->label('Veröffentlicht am'),
                            ])
                            ->columns(2),
                        Tab::make('Cards')
                            ->schema([
                                TextInput::make('cards.title')
                                    ->label('Bereichstitel')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('cards.intro')
                                    ->label('Einleitung')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Repeater::make('cards.items')
                                    ->label('Cards')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Titel')
                                            ->required()
                                            ->maxLength(255),
                                        Textarea::make('content')
                                            ->label('Text')
                                            ->rows(3)
                                            ->required()
                                            ->columnSpanFull(),
                                        TextInput::make('icon')
                                            ->label('Icon')
                                            ->maxLength(255),
                                        TextInput::make('link_label')
                                            ->label('Link-Label')
                                            ->maxLength(255),
                                        TextInput::make('link_url')
                                            ->label('Link-URL')
                                            ->maxLength(255),
                                        TextInput::make('sort_order')
                                            ->label('Sortierung')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                        Toggle::make('is_active')
                                            ->label('Aktiv')
                                            ->default(true),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        Tab::make('FAQ')
                            ->schema([
                                TextInput::make('faq.title')
                                    ->label('Bereichstitel')
                                    ->required()
                                    ->maxLength(255),
                                Repeater::make('faq.items')
                                    ->label('Fragen')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Frage')
                                            ->required()
                                            ->maxLength(255),
                                        Textarea::make('content')
                                            ->label('Antwort')
                                            ->rows(4)
                                            ->required()
                                            ->columnSpanFull(),
                                        TextInput::make('sort_order')
                                            ->label('Sortierung')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                        Toggle::make('is_active')
                                            ->label('Aktiv')
                                            ->default(true),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Downloads')
                            ->schema([
                                TextInput::make('downloads.application.title')
                                    ->label('Titel Beitrittserklärung')
                                    ->maxLength(255),
                                Textarea::make('downloads.application.description')
                                    ->label('Beschreibung Beitrittserklärung')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                FileUpload::make('downloads.application.pdf_path')
                                    ->label('PDF Beitrittserklärung')
                                    ->disk('public')
                                    ->directory('membership')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->openable()
                                    ->downloadable()
                                    ->afterStateUpdated(function (Set $set, mixed $state): void {
                                        if (! $state instanceof TemporaryUploadedFile) {
                                            return;
                                        }

                                        $set('downloads.application.original_filename', $state->getClientOriginalName());
                                        $set('downloads.application.mime_type', $state->getMimeType() ?? 'application/pdf');
                                        $set('downloads.application.file_size', $state->getSize());
                                    }),
                                Hidden::make('downloads.application.original_filename'),
                                Hidden::make('downloads.application.mime_type'),
                                Hidden::make('downloads.application.file_size'),
                                TextInput::make('downloads.flyer.title')
                                    ->label('Titel Flyer')
                                    ->maxLength(255),
                                FileUpload::make('downloads.flyer.pdf_path')
                                    ->label('PDF Flyer')
                                    ->disk('public')
                                    ->directory('flyers')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->openable()
                                    ->downloadable()
                                    ->afterStateUpdated(function (Set $set, mixed $state): void {
                                        if (! $state instanceof TemporaryUploadedFile) {
                                            return;
                                        }

                                        $set('downloads.flyer.original_filename', $state->getClientOriginalName());
                                        $set('downloads.flyer.mime_type', $state->getMimeType() ?? 'application/pdf');
                                        $set('downloads.flyer.file_size', $state->getSize());
                                        $set('downloads.flyer.title', Str::of($state->getClientOriginalName())->beforeLast('.')->replace(['-', '_'], ' ')->title()->value());
                                    }),
                                Hidden::make('downloads.flyer.original_filename'),
                                Hidden::make('downloads.flyer.mime_type'),
                                Hidden::make('downloads.flyer.file_size'),
                            ])
                            ->columns(2),
                        Tab::make('Hinweise')
                            ->schema([
                                TextInput::make('notes.primary_title')
                                    ->label('Titel Hinweis 1')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('notes.primary_content')
                                    ->label('Hinweis 1')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('notes.secondary_title')
                                    ->label('Titel Hinweis 2')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('notes.secondary_content')
                                    ->label('Hinweis 2')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('notes.contact_url')
                                    ->label('Kontakt-URL')
                                    ->maxLength(255)
                                    ->required(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $page = $this->savePage($state['general']);

        $this->saveHeroSection($page, $state['general']);
        $this->saveCardsSection($page, $state['cards']);
        $this->saveFaqSection($page, $state['faq']);
        $this->saveNotesSections($page, $state['notes']);
        $this->saveContactLink($state['notes']['contact_url']);
        $this->saveApplicationDocument($state['downloads']['application']);
        $this->saveFlyerDocument($state['downloads']['flyer']);

        Notification::make()
            ->title('Mitglied werden wurde gespeichert.')
            ->success()
            ->send();

        $this->form->fill($this->getFormDefaults());
    }

    protected function getFormDefaults(): array
    {
        $page = $this->getMembershipPage();
        $hero = $this->getSection('membership-hero');
        $cards = $this->getSection('membership-offers');
        $faq = $this->getSection('membership-faq');
        $primaryNote = $this->getSection('practical-note-1');
        $secondaryNote = $this->getSection('practical-note-2');
        $contactLink = ExternalLink::query()->where('link_key', 'page.mitglied-werden.contact')->first();
        $applicationDocument = MembershipDocument::query()->current('application')->first();
        $flyer = Flyer::query()->current()->first();

        return [
            'general' => [
                'page_title' => $page?->title ?? 'Mitglied werden',
                'nav_label' => $page?->nav_label ?? 'Mitglied werden',
                'hero_subtitle' => $hero?->subtitle,
                'hero_intro' => $hero?->content ?? '',
                'meta_title' => $page?->meta_title,
                'meta_description' => $page?->meta_description,
                'status' => $page?->status ?? 'draft',
                'published_at' => $page?->published_at,
            ],
            'cards' => [
                'title' => $cards?->title ?? 'Alles für den Einstieg',
                'intro' => $cards?->content,
                'items' => collect($cards?->data['items'] ?? [])
                    ->map(fn (array $item): array => [
                        'title' => $item['title'] ?? '',
                        'content' => $item['content'] ?? '',
                        'icon' => $item['icon'] ?? null,
                        'link_label' => $item['link_label'] ?? null,
                        'link_url' => $item['link_url'] ?? null,
                        'sort_order' => (int) ($item['sort_order'] ?? 0),
                        'is_active' => (bool) ($item['is_active'] ?? true),
                    ])
                    ->sortBy('sort_order')
                    ->values()
                    ->all(),
            ],
            'faq' => [
                'title' => $faq?->title ?? 'Häufige Fragen',
                'items' => collect($faq?->data['items'] ?? [])
                    ->map(fn (array $item): array => [
                        'title' => $item['title'] ?? '',
                        'content' => $item['content'] ?? '',
                        'sort_order' => (int) ($item['sort_order'] ?? 0),
                        'is_active' => (bool) ($item['is_active'] ?? true),
                    ])
                    ->sortBy('sort_order')
                    ->values()
                    ->all(),
            ],
            'notes' => [
                'primary_title' => $primaryNote?->title ?? 'Wichtiger Hinweis',
                'primary_content' => $primaryNote?->content ?? '',
                'secondary_title' => $secondaryNote?->title ?? 'Kontakt',
                'secondary_content' => $secondaryNote?->content ?? '',
                'contact_url' => $contactLink?->url ?? '',
            ],
            'downloads' => [
                'application' => [
                    'title' => $applicationDocument?->title ?? 'Beitrittserklärung',
                    'description' => $applicationDocument?->description,
                    'pdf_path' => $applicationDocument?->pdf_path,
                    'original_filename' => $applicationDocument?->original_filename,
                    'mime_type' => $applicationDocument?->mime_type,
                    'file_size' => $applicationDocument?->file_size,
                ],
                'flyer' => [
                    'title' => $flyer?->title ?? 'Flyer',
                    'pdf_path' => $flyer?->pdf_path,
                    'original_filename' => $flyer?->original_filename,
                    'mime_type' => $flyer?->mime_type,
                    'file_size' => $flyer?->file_size,
                ],
            ],
        ];
    }

    protected function savePage(array $data): PageModel
    {
        return PageModel::query()->updateOrCreate(
            ['slug' => 'mitglied-werden'],
            [
                'title' => $data['page_title'],
                'nav_label' => $data['nav_label'],
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'status' => $data['status'],
                'published_at' => $data['published_at'],
                'sort_order' => 90,
            ],
        );
    }

    protected function saveHeroSection(PageModel $page, array $data): void
    {
        PageSection::query()->updateOrCreate(
            [
                'page_id' => $page->id,
                'section_key' => 'membership-hero',
            ],
            [
                'page_id' => $page->id,
                'section_type' => 'hero',
                'title' => $data['page_title'],
                'subtitle' => $data['hero_subtitle'],
                'content' => $data['hero_intro'],
                'data' => ['accent' => 'primary'],
                'sort_order' => 10,
            ],
        );
    }

    protected function saveCardsSection(PageModel $page, array $data): void
    {
        PageSection::query()->updateOrCreate(
            [
                'page_id' => $page->id,
                'section_key' => 'membership-offers',
            ],
            [
                'page_id' => $page->id,
                'section_type' => 'cards',
                'title' => $data['title'],
                'content' => $data['intro'],
                'data' => [
                    'layout' => 'grid-2',
                    'items' => collect($data['items'] ?? [])
                        ->map(fn (array $item): array => [
                            'title' => $item['title'],
                            'content' => $item['content'],
                            'icon' => $item['icon'] ?? null,
                            'link_label' => $item['link_label'] ?? null,
                            'link_url' => $item['link_url'] ?? null,
                            'sort_order' => (int) ($item['sort_order'] ?? 0),
                            'is_active' => (bool) ($item['is_active'] ?? true),
                        ])
                        ->values()
                        ->all(),
                ],
                'sort_order' => 20,
            ],
        );
    }

    protected function saveFaqSection(PageModel $page, array $data): void
    {
        PageSection::query()->updateOrCreate(
            [
                'page_id' => $page->id,
                'section_key' => 'membership-faq',
            ],
            [
                'page_id' => $page->id,
                'section_type' => 'faq',
                'title' => $data['title'],
                'content' => null,
                'data' => [
                    'items' => collect($data['items'] ?? [])
                        ->map(fn (array $item): array => [
                            'title' => $item['title'],
                            'content' => $item['content'],
                            'sort_order' => (int) ($item['sort_order'] ?? 0),
                            'is_active' => (bool) ($item['is_active'] ?? true),
                        ])
                        ->values()
                        ->all(),
                ],
                'sort_order' => 50,
            ],
        );
    }

    protected function saveNotesSections(PageModel $page, array $data): void
    {
        PageSection::query()->updateOrCreate(
            [
                'page_id' => $page->id,
                'section_key' => 'practical-note-1',
            ],
            [
                'page_id' => $page->id,
                'section_type' => 'notice',
                'title' => $data['primary_title'],
                'content' => $data['primary_content'],
                'data' => ['tone' => 'info'],
                'sort_order' => 30,
            ],
        );

        PageSection::query()->updateOrCreate(
            [
                'page_id' => $page->id,
                'section_key' => 'practical-note-2',
            ],
            [
                'page_id' => $page->id,
                'section_type' => 'notice',
                'title' => $data['secondary_title'],
                'content' => $data['secondary_content'],
                'data' => ['tone' => 'info'],
                'sort_order' => 40,
            ],
        );
    }

    protected function saveContactLink(string $url): void
    {
        ExternalLink::query()->updateOrCreate(
            ['link_key' => 'page.mitglied-werden.contact'],
            [
                'label' => 'Kontakt Mitglied werden',
                'url' => $url,
                'description' => 'Kontakt zur Mitgliedschaft',
            ],
        );
    }

    protected function saveApplicationDocument(array $data): void
    {
        $currentDocument = MembershipDocument::query()->current('application')->first();
        $path = $data['pdf_path'] ?? null;

        if (! is_string($path) || $path === '') {
            return;
        }

        if ($currentDocument !== null && $currentDocument->pdf_path === $path) {
            $currentDocument->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'original_filename' => $data['original_filename'] ?? $currentDocument->original_filename,
                'mime_type' => $data['mime_type'] ?? $currentDocument->mime_type,
                'file_size' => $data['file_size'] ?? $currentDocument->file_size,
                'is_active' => true,
            ]);

            return;
        }

        MembershipDocument::query()->create([
            'document_type' => 'application',
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'pdf_path' => $path,
            'original_filename' => $data['original_filename'] ?? basename($path),
            'mime_type' => $data['mime_type'] ?? 'application/pdf',
            'file_size' => $data['file_size'] ?? null,
            'is_active' => true,
        ]);
    }

    protected function saveFlyerDocument(array $data): void
    {
        $currentFlyer = Flyer::query()->current()->first();
        $path = $data['pdf_path'] ?? null;

        if (! is_string($path) || $path === '') {
            return;
        }

        if ($currentFlyer !== null && $currentFlyer->pdf_path === $path) {
            $currentFlyer->update([
                'title' => $data['title'],
                'original_filename' => $data['original_filename'] ?? $currentFlyer->original_filename,
                'mime_type' => $data['mime_type'] ?? $currentFlyer->mime_type,
                'file_size' => $data['file_size'] ?? $currentFlyer->file_size,
                'is_active' => true,
            ]);

            return;
        }

        Flyer::query()->create([
            'title' => $data['title'],
            'pdf_path' => $path,
            'original_filename' => $data['original_filename'] ?? basename($path),
            'mime_type' => $data['mime_type'] ?? 'application/pdf',
            'file_size' => $data['file_size'] ?? null,
            'is_active' => true,
        ]);
    }

    protected function getMembershipPage(): ?PageModel
    {
        return PageModel::query()->where('slug', 'mitglied-werden')->first();
    }

    protected function getSection(string $sectionKey): ?PageSection
    {
        $page = $this->getMembershipPage();

        if ($page === null) {
            return null;
        }

        return PageSection::query()
            ->where('page_id', $page->id)
            ->where('section_key', $sectionKey)
            ->first();
    }
}
