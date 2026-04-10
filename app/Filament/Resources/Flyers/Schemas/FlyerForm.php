<?php

namespace App\Filament\Resources\Flyers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FlyerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titel')
                    ->maxLength(255),
                FileUpload::make('pdf_path')
                    ->label('PDF-Datei')
                    ->disk('public')
                    ->directory('flyers')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required()
                    ->openable()
                    ->downloadable()
                    ->afterStateUpdated(function (Set $set, mixed $state): void {
                        if (! $state instanceof TemporaryUploadedFile) {
                            return;
                        }

                        $set('original_filename', $state->getClientOriginalName());
                        $set('mime_type', $state->getMimeType() ?? 'application/pdf');
                        $set('file_size', $state->getSize());
                        $set('title', Str::of($state->getClientOriginalName())->beforeLast('.')->replace(['-', '_'], ' ')->title()->value());
                        $set('is_active', true);
                    }),
                Hidden::make('original_filename'),
                Hidden::make('mime_type'),
                Hidden::make('file_size'),
                Hidden::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
