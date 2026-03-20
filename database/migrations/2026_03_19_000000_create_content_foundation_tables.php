<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('nav_label')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table): void {
            $table->id();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('filename');
            $table->string('mime_type');
            $table->string('extension')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('title')->nullable();
            $table->string('alt_text')->nullable();
            $table->timestamps();
        });

        Schema::create('external_links', function (Blueprint $table): void {
            $table->id();
            $table->string('link_key')->unique();
            $table->string('label');
            $table->string('url');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('people', function (Blueprint $table): void {
            $table->id();
            $table->string('display_name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->foreignId('portrait_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('role_key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('venues', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('street')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('chronicles', function (Blueprint $table): void {
            $table->id();
            $table->string('chronicle_key')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('competition_types', function (Blueprint $table): void {
            $table->id();
            $table->string('type_key')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('page_sections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('section_key')->nullable();
            $table->enum('section_type', ['hero', 'rich_text', 'notice', 'faq', 'cards', 'cta']);
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('content')->nullable();
            $table->json('data')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('role_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('person_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->string('label_override')->nullable();
            $table->date('started_on')->nullable();
            $table->date('ended_on')->nullable();
            $table->boolean('is_current')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('display_date_text')->nullable();
            $table->string('month_label')->nullable();
            $table->string('audience_text')->nullable();
            $table->string('source_url')->nullable();
            $table->string('external_ics_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('chronicle_entries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('chronicle_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->string('title')->nullable();
            $table->string('headline')->nullable();
            $table->text('pair_text');
            $table->text('secondary_text')->nullable();
            $table->foreignId('image_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('external_image_url')->nullable();
            $table->string('source_url')->nullable();
            $table->boolean('is_highlighted')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['chronicle_id', 'year']);
        });

        Schema::create('competitions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('competition_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('competition_result_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('competition_results', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('competition_result_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->nullable()->constrained()->nullOnDelete();
            $table->string('winner_name');
            $table->unsignedTinyInteger('rank');
            $table->decimal('score', 8, 2)->nullable();
            $table->string('score_text')->nullable();
            $table->timestamps();
        });

        Schema::create('plaque_award_rules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->enum('rule_type', ['ring_threshold', 'gold_milestone']);
            $table->unsignedTinyInteger('age_from')->nullable();
            $table->unsignedTinyInteger('age_to')->nullable();
            $table->unsignedTinyInteger('required_score')->nullable();
            $table->unsignedSmallInteger('required_gold_count')->nullable();
            $table->string('award_name');
            $table->string('award_level')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plaque_award_rules');
        Schema::dropIfExists('competition_results');
        Schema::dropIfExists('competition_result_categories');
        Schema::dropIfExists('competitions');
        Schema::dropIfExists('chronicle_entries');
        Schema::dropIfExists('events');
        Schema::dropIfExists('role_assignments');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('competition_types');
        Schema::dropIfExists('chronicles');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('people');
        Schema::dropIfExists('external_links');
        Schema::dropIfExists('media');
        Schema::dropIfExists('pages');
    }
};
