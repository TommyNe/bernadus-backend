<?php

use App\Models\MembershipDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('keeps only the newest active membership document per type', function () {
    $firstDocument = MembershipDocument::factory()->create([
        'document_type' => 'application',
        'title' => 'Beitrittserklärung 2025',
        'is_active' => true,
    ]);

    $secondDocument = MembershipDocument::factory()->create([
        'document_type' => 'application',
        'title' => 'Beitrittserklärung 2026',
        'is_active' => true,
    ]);

    expect($firstDocument->fresh()->is_active)->toBeFalse()
        ->and($secondDocument->fresh()->is_active)->toBeTrue();
});
