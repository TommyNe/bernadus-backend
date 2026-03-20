<?php

use Database\Seeders\ContentFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('creates the planned core content tables', function () {
    $tables = [
        'pages',
        'page_sections',
        'media',
        'external_links',
        'people',
        'roles',
        'role_assignments',
        'venues',
        'events',
        'chronicles',
        'chronicle_entries',
        'competition_types',
        'competitions',
        'competition_result_categories',
        'competition_results',
        'plaque_award_rules',
    ];

    foreach ($tables as $table) {
        expect(Schema::hasTable($table))->toBeTrue();
    }
});

it('applies core defaults and foreign keys for the editorial schema', function () {
    expect(Schema::hasColumns('pages', [
        'slug',
        'title',
        'status',
        'published_at',
        'sort_order',
    ]))->toBeTrue();

    expect(Schema::hasColumns('page_sections', [
        'page_id',
        'section_type',
        'data',
    ]))->toBeTrue();

    expect(Schema::hasColumns('events', [
        'venue_id',
        'starts_at',
        'external_ics_url',
    ]))->toBeTrue();

    $pageStatus = collect(DB::select('pragma table_info(pages)'))
        ->firstWhere('name', 'status');

    $allDay = collect(DB::select('pragma table_info(events)'))
        ->firstWhere('name', 'all_day');

    expect($pageStatus?->dflt_value)->toBe("'draft'");
    expect($allDay?->dflt_value)->toBe("'0'");
});

it('seeds the expected reference records for the slim content backend', function () {
    $this->seed(ContentFoundationSeeder::class);

    expect(DB::table('pages')->count())->toBe(16);
    expect(DB::table('external_links')->count())->toBe(10);
    expect(DB::table('roles')->orderBy('sort_order')->pluck('role_key')->all())->toBe([
        'vorsitzender',
        'oberst',
        'oberstleutnant',
        'schriftfuehrer',
        'kassenfuehrer',
    ]);
    expect(DB::table('chronicles')->orderBy('sort_order')->pluck('chronicle_key')->all())->toBe([
        'shooting_kings',
        'child_kings',
    ]);
    expect(DB::table('competition_types')->orderBy('type_key')->pluck('type_key')->all())->toBe([
        'plaque_shooting',
        'trophy_shooting',
    ]);
});
