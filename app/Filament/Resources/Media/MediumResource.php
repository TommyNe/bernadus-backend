<?php

namespace App\Filament\Resources\Media;

use App\Filament\Resources\Media\Pages\CreateMedium;
use App\Filament\Resources\Media\Pages\EditMedium;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Models\Medium;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediumResource extends Resource
{
    protected static ?string $model = Medium::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $modelLabel = 'Medium';

    protected static ?string $pluralModelLabel = 'Medien';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?string $navigationLabel = 'Medien';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'filename';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('path')
                    ->label('Bild')
                    ->image()
                    ->disk('public')
                    ->directory('media')
                    ->required()
                    ->openable()
                    ->downloadable()
                    ->imageEditor()
                    ->afterStateUpdated(function (Set $set, mixed $state): void {
                        if (! $state instanceof TemporaryUploadedFile) {
                            return;
                        }

                        static::populateImageMetadata($set, $state);
                    }),
                TextInput::make('title')
                    ->maxLength(255),
                Textarea::make('alt_text')
                    ->label('Alternativtext')
                    ->rows(2)
                    ->columnSpanFull(),
                Hidden::make('disk')
                    ->default('public')
                    ->required(),
                Hidden::make('filename')
                    ->required(),
                Hidden::make('mime_type')
                    ->required(),
                Hidden::make('extension'),
                Hidden::make('size'),
                Hidden::make('width'),
                Hidden::make('height'),
            ]);
    }

    protected static function populateImageMetadata(Set $set, TemporaryUploadedFile $file): void
    {
        $set('disk', 'public');
        $set('filename', $file->getClientOriginalName());
        $set('mime_type', $file->getMimeType() ?? 'application/octet-stream');
        $set('extension', Str::lower($file->getClientOriginalExtension()));
        $set('size', $file->getSize());

        ['width' => $width, 'height' => $height] = static::getImageDimensions($file);

        $set('width', $width);
        $set('height', $height);
    }

    /**
     * @return array{width: int|null, height: int|null}
     */
    protected static function getImageDimensions(TemporaryUploadedFile $file): array
    {
        $dimensions = @getimagesize($file->getRealPath());

        if (! is_array($dimensions)) {
            return [
                'width' => null,
                'height' => null,
            ];
        }

        return [
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('filename')
            ->columns([
                ImageColumn::make('path')
                    ->label('Vorschau')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Titel')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('filename')
                    ->label('Datei')
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->label('Typ')
                    ->badge(),
                TextColumn::make('size')
                    ->label('Größe')
                    ->numeric(),
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedia::route('/'),
            'create' => CreateMedium::route('/create'),
            'edit' => EditMedium::route('/{record}/edit'),
        ];
    }
}
