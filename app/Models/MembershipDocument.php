<?php

namespace App\Models;

use Database\Factories\MembershipDocumentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MembershipDocument extends Model
{
    /** @use HasFactory<MembershipDocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'document_type',
        'title',
        'description',
        'pdf_path',
        'original_filename',
        'mime_type',
        'file_size',
        'is_active',
        'uploaded_at',
    ];

    public static function typeOptions(): array
    {
        return [
            'application' => 'Beitrittserklärung',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (MembershipDocument $document): void {
            $document->uploaded_at ??= now();

            if (! $document->is_active) {
                return;
            }

            static::query()
                ->where('document_type', $document->document_type)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        });

        static::updating(function (MembershipDocument $document): void {
            if ($document->isDirty('pdf_path')) {
                $document->uploaded_at = now();
            }

            if (! $document->is_active) {
                return;
            }

            static::query()
                ->where('document_type', $document->document_type)
                ->whereKeyNot($document->getKey())
                ->where('is_active', true)
                ->update(['is_active' => false]);
        });

        static::updated(function (MembershipDocument $document): void {
            if (! $document->wasChanged('pdf_path')) {
                return;
            }

            $originalPdfPath = $document->getOriginal('pdf_path');

            if (! is_string($originalPdfPath) || $originalPdfPath === '' || $originalPdfPath === $document->pdf_path) {
                return;
            }

            Storage::disk('public')->delete($originalPdfPath);
        });

        static::deleted(function (MembershipDocument $document): void {
            if (! is_string($document->pdf_path) || $document->pdf_path === '') {
                return;
            }

            Storage::disk('public')->delete($document->pdf_path);
        });
    }

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'is_active' => 'boolean',
            'uploaded_at' => 'datetime',
        ];
    }

    public function scopeCurrent(Builder $query, string $documentType): Builder
    {
        return $query
            ->where('document_type', $documentType)
            ->where('is_active', true)
            ->orderByDesc('uploaded_at')
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }
}
